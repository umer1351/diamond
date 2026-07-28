<?php

namespace App\Http\Controllers;

use App\Models\FinishProduct;
use App\Models\CmsHomeSection;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use App\Models\Product;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    private array $galleryImages = [];

    /**
     * The only categories the storefront should surface, in display order.
     * Matched against the Product (category) name, case-insensitively.
     */
    private const STOREFRONT_CATEGORIES = [
        'Necklace Set',
        'Daily Wear',
        'Occasion',
        'Limited Edition',
        'Pendant',
        'Bangles',
        'Bracelets',
        'Rings',
    ];

    /**
     * Fetch the active categories, filtered to STOREFRONT_CATEGORIES and
     * sorted into that exact order, each with its (active) finish products.
     */
    private function storefrontCategories(): Collection
    {
        $names = collect(self::STOREFRONT_CATEGORIES);
        $lookup = $names->mapWithKeys(fn ($name, $index) => [mb_strtolower($name) => $index]);

        return Product::where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereIn(DB::raw('LOWER(name)'), $names->map(fn ($n) => mb_strtolower($n))->all())
            ->with(['finishProducts' => function ($query) {
                $query->where('is_deleted', 0)->where('is_active', 1)->latest();
            }])
            ->withCount(['finishProducts' => function ($query) {
                $query->where('is_deleted', 0)->where('is_active', 1);
            }])
            ->get()
            ->sortBy(fn (Product $category) => $lookup->get(mb_strtolower(trim($category->name)), 999))
            ->values()
            ->map(function (Product $category, int $index) {
                $category->category_image_url = $this->categoryImageUrl($category, $index + 1);

                return $category;
            });
    }

    private function storefrontQuery()
    {
        return FinishProduct::with(['product', 'warehouse', 'job_purchase_detail', 'images'])
            ->where('is_deleted', 0)
            ->where('is_active', 1);
    }

    private function applyProductSearch($query, string $term): void
    {
        $query->where(function ($builder) use ($term) {
            $builder->where('tag_no', 'like', '%' . $term . '%')
                ->orWhere('product_name', 'like', '%' . $term . '%')
                ->orWhere('short_description', 'like', '%' . $term . '%')
                ->orWhere('long_description', 'like', '%' . $term . '%')
                ->orWhere('tags', 'like', '%' . $term . '%')
                ->orWhereHas('job_purchase_detail', function ($detailQuery) use ($term) {
                    $detailQuery->where('design_no', 'like', '%' . $term . '%');
                })
                ->orWhereHas('product', function ($productQuery) use ($term) {
                    $productQuery->where('name', 'like', '%' . $term . '%')
                        ->orWhere('prefix', 'like', '%' . $term . '%');
                });
        });
    }

    private function galleryImages(): array
    {
        if ($this->galleryImages !== []) {
            return $this->galleryImages;
        }

        $files = glob(public_path('assets/images/storefront-gallery/*.{jpg,jpeg,png,JPG,JPEG,PNG}'), GLOB_BRACE) ?: [];
        sort($files);

        $this->galleryImages = array_values(array_map(function ($file) {
            return asset('assets/images/storefront-gallery/' . basename($file));
        }, $files));

        return $this->galleryImages;
    }

    private function fallbackImage(int $seed, string $fallback): string
    {
        $images = $this->galleryImages();

        if ($images === []) {
            return asset($fallback);
        }

        return $images[$seed % count($images)];
    }

    private function imageUrl(?string $path, int $seed, string $fallback): string
    {
        $path = trim((string) $path);

        if ($path !== '') {
            $normalized = ltrim(str_replace('\\', '/', $path), '/');
            $absolute = public_path($normalized);

            if (is_file($absolute)) {
                return asset($normalized);
            }
        }

        return $this->fallbackImage($seed, $fallback);
    }

    /**
     * Resolve a product's real, on-disk image URLs (gallery first, then the
     * legacy single `picture`). Never falls back to random placeholder images.
     */
    private function productImageUrls(FinishProduct $item): array
    {
        $paths = [];

        if ($item->relationLoaded('images') || $item->images()->exists()) {
            foreach ($item->images as $image) {
                $paths[] = $image->path;
            }
        }

        if ($paths === [] && trim((string) $item->picture) !== '') {
            $paths[] = $item->picture;
        }

        $urls = [];
        foreach ($paths as $path) {
            $normalized = ltrim(str_replace('\\', '/', trim((string) $path)), '/');
            if ($normalized !== '' && is_file(public_path($normalized))) {
                $urls[] = asset($normalized);
            }
        }

        return array_values(array_unique($urls));
    }

    private function enrichProduct(FinishProduct $item): FinishProduct
    {
        $seed = (int) $item->id;
        $urls = $this->productImageUrls($item);

        $item->image_url = $urls[0] ?? $this->fallbackImage($seed, 'assets/images/photo-wide-1.jpg');
        $item->hover_image_url = $urls[1] ?? $item->image_url;
        $item->gallery = $urls !== [] ? $urls : [$item->image_url];
        $item->display_name = $item->product_name ?: ($item->product->name ?? 'Jewellery Piece');
        $item->display_description = $item->short_description ?: ($item->long_description ?: '');
        $item->display_price = (float) ($item->total_amount ?? 0);
        $item->display_weight = (float) ($item->gross_weight ?? $item->net_weight ?? 0);
        $manualStock = $item->product ? $item->product->getRawOriginal('stock') : null;
        $item->stock_level = $manualStock !== null && $manualStock !== ''
            ? (int) $manualStock
            : (int) FinishProduct::where('product_id', $item->product_id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->count();
        $item->design_no = $item->job_purchase_detail->design_no ?? null;

        // Available sizes (Bangles / Rings). The `sizes` column is a free-form
        // comma / slash / pipe separated list e.g. "2.6, 2.8, 2.1" or "6,7,8".
        $item->size_options = collect(preg_split('/[,;|]+/', (string) $item->sizes))
            ->map(fn ($size) => trim((string) $size))
            ->filter(fn ($size) => $size !== '' && strtoupper($size) !== 'N/A')
            ->unique()
            ->values()
            ->all();

        $categoryName = mb_strtolower(trim((string) ($item->product->name ?? '')));
        $item->sizes_enabled = in_array($categoryName, ['bangles', 'rings'], true);

        return $item;
    }

    private function enrichCollection(iterable $items): Collection
    {
        return collect($items)->map(function (FinishProduct $item) {
            return $this->enrichProduct($item);
        })->values();
    }

    private function getCartMap(): array
    {
        $cart = Session::get('storefront_cart', []);

        return is_array($cart) ? $cart : [];
    }

    private function getCartSizes(): array
    {
        $sizes = Session::get('storefront_cart_sizes', []);

        return is_array($sizes) ? $sizes : [];
    }

    private function cartItems(): Collection
    {
        $cart = $this->getCartMap();
        $ids = array_keys(array_filter($cart, fn ($qty) => (int) $qty > 0));

        if ($ids === []) {
            return collect();
        }

        $products = $this->storefrontQuery()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($qty, $id) use ($products) {
            $product = $products->get((int) $id);

            if (! $product) {
                return null;
            }

            $item = $this->enrichProduct($product);
            $item->cart_quantity = max(1, (int) $qty);
            $item->cart_total = $item->display_price * $item->cart_quantity;
            $item->cart_size = $this->getCartSizes()[(int) $id] ?? null;

            return $item;
        })->filter()->values();
    }

    private function cartSummary(): array
    {
        $items = $this->cartItems();
        $subTotal = (float) $items->sum('cart_total');
        $shipping = $items->isEmpty() ? 0.0 : 35.0;

        return [
            'items' => $items,
            'item_count' => (int) $items->sum('cart_quantity'),
            'sub_total' => $subTotal,
            'shipping' => $shipping,
            'grand_total' => $subTotal + $shipping,
        ];
    }

    private function cmsSetting(): CmsSetting
    {
        return CmsSetting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Azure Luxury',
                'logo_text' => 'AZURE',
                'footer_about' => 'Luxury jewellery and gift storefront powered by ERP data.',
                'footer_email' => 'info@azureluxury.com',
                'footer_phone' => '+97450903133',
                'footer_address' => 'Qatar',
                'copyright_text' => '2026 Azure Luxury - Qatar',
                'is_active' => 1,
            ]
        );
    }

    private function cmsSections(): Collection
    {
        return CmsHomeSection::where('is_active', 1)->orderBy('sort_order')->get();
    }

    private function cmsSectionImage(?CmsHomeSection $section): ?string
    {
        if (! $section || blank($section->image_path)) {
            return null;
        }

        if (Storage::disk('public')->exists($section->image_path)) {
            return Storage::url($section->image_path);
        }

        $normalized = ltrim(str_replace('\\', '/', $section->image_path), '/');

        if (is_file(public_path($normalized))) {
            return asset($normalized);
        }

        return null;
    }

    private function cmsPage(string $slug): ?CmsPage
    {
        return CmsPage::where('slug', $slug)->where('is_active', 1)->first();
    }

    private function cmsSectionMap(): Collection
    {
        return $this->cmsSections()->mapWithKeys(function (CmsHomeSection $section) {
            return [
                $section->key => [
                    'section' => $section,
                    'image_url' => $this->cmsSectionImage($section) ?? $this->fallbackImage($section->id, 'assets/images/photo-wide-4.jpg'),
                    'media_url' => $this->cmsSectionMedia($section),
                ],
            ];
        });
    }

    private function cmsSectionMedia(CmsHomeSection $section): ?string
    {
        if ($section->media_type === 'video' && filled($section->image_path)) {
            if (Storage::disk('public')->exists($section->image_path)) {
                return Storage::url($section->image_path);
            }

            $normalized = ltrim(str_replace('\\', '/', $section->image_path), '/');

            if (is_file(public_path($normalized))) {
                return asset($normalized);
            }
        }

        return filled($section->media_url) ? $section->media_url : $section->button_url;
    }

    private function youtubeEmbedUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&loop=1&playlist=' . $matches[1] . '&rel=0&controls=1&playsinline=1';
        }

        return $url;
    }

    private function categoryImageUrl(Product $category, int $seed): string
    {
        $path = trim((string) $category->image_path);

        if ($path !== '') {
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }

            $normalized = ltrim(str_replace('\\', '/', $path), '/');

            if (is_file(public_path($normalized))) {
                return asset($normalized);
            }
        }

        $firstProduct = $category->finishProducts->first();

        if ($firstProduct) {
            return $this->imageUrl($firstProduct->picture, $seed, 'assets/images/photo-wide-3.jpg');
        }

        return $this->fallbackImage($seed, 'assets/images/photo-wide-3.jpg');
    }

    private function qpayConfigured(): bool
    {
        return filled(config('services.qpay.merchant_id')) && filled(config('services.qpay.secret'));
    }

    private function paypalConfigured(): bool
    {
        return filled(config('services.paypal.client_id')) && filled(config('services.paypal.secret'));
    }

    private function qpayPayload(Collection $items, float $shipping, array $customer): array
    {
        return [
            'merchant_id' => config('services.qpay.merchant_id'),
            'currency' => config('services.qpay.currency', 'qar'),
            'amount' => number_format((float) ($items->sum('cart_total') + $shipping), 2, '.', ''),
            'customer_name' => $customer['customer_name'] ?? '',
            'customer_email' => $customer['email'] ?? '',
            'customer_phone' => $customer['phone'] ?? '',
            'reference' => 'ORD-' . now()->format('YmdHis'),
            'items' => $items->map(function (FinishProduct $item) {
                return [
                    'name' => $item->display_name,
                    'quantity' => $item->cart_quantity,
                    'price' => number_format($item->display_price, 2, '.', ''),
                ];
            })->values()->all(),
            'shipping' => number_format($shipping, 2, '.', ''),
            'success_url' => route('storefront.checkout.success'),
            'cancel_url' => route('storefront.checkout.cancel'),
        ];
    }

    public function index(Request $request): View
    {
        $query = $this->storefrontQuery();
        $term = trim((string) $request->input('q', ''));

        if ($term !== '') {
            $this->applyProductSearch($query, $term);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', (int) $request->input('product_id'));
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $products->setCollection($this->enrichCollection($products->getCollection()));

        $catalog = $this->enrichCollection($this->storefrontQuery()->latest()->get());
        $newArrivals = $catalog->take(8);
        $bestSellers = $catalog->sortByDesc('display_price')->take(8)->values();
        $giftEdit = $catalog->sortBy('display_price')->take(8)->values();
        $cmsSetting = $this->cmsSetting();
        $cmsSections = $this->cmsSections()->keyBy('key');
        $cmsImages = $this->cmsSectionMap();
        $heroVideoUrl = $this->youtubeEmbedUrl(optional($cmsImages->get('hero_video'))['media_url'] ?? 'https://www.youtube.com/watch?v=Xc0krzV1jbc');

        $categories = $this->storefrontCategories();

        // Each category section shows a banner + one row of 4 products + a
        // "More" link. Enrich only the first 4 finish products per category.
        $homeCategories = $categories->map(function (Product $category) {
            $category->preview_products = $this->enrichCollection(
                $category->finishProducts->take(4)
            );

            return $category;
        })->filter(fn (Product $category) => $category->preview_products->isNotEmpty())->values();

        return view('storefront.index', [
            'products' => $products,
            'categories' => $categories,
            'homeCategories' => $homeCategories,
            'searchTerm' => $term,
            'newArrivals' => $newArrivals,
            'bestSellers' => $bestSellers,
            'giftEdit' => $giftEdit,
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $cmsSetting,
            'cmsSections' => $cmsSections,
            'cmsImages' => $cmsImages,
            'heroVideoUrl' => $heroVideoUrl,
        ]);
    }

    public function categories(Request $request): View
    {
        $categories = $this->storefrontCategories();

        $selectedCategory = null;
        $productsQuery = $this->storefrontQuery();

        if ($request->filled('category')) {
            $selectedCategory = Product::where('is_deleted', 0)
                ->where('is_active', 1)
                ->find((int) $request->input('category'));

            if ($selectedCategory) {
                $productsQuery->where('product_id', $selectedCategory->id);
            }
        }

        $products = $productsQuery->latest()->paginate(12)->withQueryString();
        $products->setCollection($this->enrichCollection($products->getCollection()));

        return view('storefront.categories', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'products' => $products,
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function search(Request $request): View
    {
        $term = trim((string) $request->input('q', ''));
        $query = $this->storefrontQuery();

        if ($term !== '') {
            $this->applyProductSearch($query, $term);
        }

        $results = $query->latest()->paginate(12)->withQueryString();
        $results->setCollection($this->enrichCollection($results->getCollection()));

        return view('storefront.search', [
            'term' => $term,
            'results' => $results,
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function contact(): View
    {
        return view('storefront.contact', [
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function show(int $id): View
    {
        $product = $this->storefrontQuery()->findOrFail($id);
        $product = $this->enrichProduct($product);

        $related = $this->enrichCollection(
            $this->storefrontQuery()
                ->where('id', '!=', $product->id)
                ->where('product_id', $product->product_id)
                ->latest()
                ->take(8)
                ->get()
        );

        if ($related->isEmpty()) {
            $related = $this->enrichCollection(
                $this->storefrontQuery()
                    ->where('id', '!=', $product->id)
                    ->latest()
                    ->take(8)
                    ->get()
            );
        }

        return view('storefront.show', [
            'product' => $product,
            'related' => $related,
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function cart(): View
    {
        $summary = $this->cartSummary();

        return view('storefront.cart', $summary + [
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function addToCart(Request $request, int $id): RedirectResponse
    {
        $product = $this->storefrontQuery()->findOrFail($id);
        $cart = $this->getCartMap();
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;
        Session::put('storefront_cart', $cart);

        $size = trim((string) $request->input('size', ''));
        if ($size !== '') {
            $sizes = $this->getCartSizes();
            $sizes[$product->id] = $size;
            Session::put('storefront_cart_sizes', $sizes);
        }

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function updateCart(Request $request, int $id): RedirectResponse
    {
        $cart = $this->getCartMap();

        if (! array_key_exists($id, $cart)) {
            return redirect()->route('storefront.cart');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart[$id] = $quantity;
        Session::put('storefront_cart', $cart);

        return redirect()->route('storefront.cart')->with('success', 'Cart updated.');
    }

    public function removeFromCart(int $id): RedirectResponse
    {
        $cart = $this->getCartMap();
        unset($cart[$id]);
        Session::put('storefront_cart', $cart);

        $sizes = $this->getCartSizes();
        unset($sizes[$id]);
        Session::put('storefront_cart_sizes', $sizes);

        return redirect()->route('storefront.cart')->with('success', 'Product removed from cart.');
    }

    public function clearCart(): RedirectResponse
    {
        Session::forget('storefront_cart');
        Session::forget('storefront_cart_sizes');

        return redirect()->route('storefront.cart')->with('success', 'Cart cleared.');
    }

    public function checkout(): View|RedirectResponse
    {
        $summary = $this->cartSummary();

        if ($summary['items']->isEmpty()) {
            return redirect()->route('storefront.index')->with('success', 'Add a product to start checkout.');
        }

        return view('storefront.checkout', $summary + [
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'qpayConfigured' => $this->qpayConfigured(),
            'paypalConfigured' => $this->paypalConfigured(),
            'paypalCurrency' => strtoupper((string) config('services.paypal.currency', 'USD')),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function payWithPaypal(Request $request, PayPalService $paypal): RedirectResponse
    {
        $summary = $this->cartSummary();

        if ($summary['items']->isEmpty()) {
            return redirect()->route('storefront.index');
        }

        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        if (! $paypal->isConfigured()) {
            return redirect()->route('storefront.checkout')->withErrors([
                'paypal' => 'PayPal is not configured. Please set PAYPAL_CLIENT_ID and PAYPAL_SECRET in the .env file.',
            ])->withInput();
        }

        $reference = 'ORD-' . now()->format('YmdHis');

        $order = $paypal->createOrder(
            (float) $summary['grand_total'],
            $reference,
            route('storefront.paypal.return'),
            route('storefront.paypal.cancel'),
        );

        if (! $order || empty($order['approve_url'])) {
            return redirect()->route('storefront.checkout')->withErrors([
                'paypal' => 'Unable to start the PayPal payment. Please try again.',
            ])->withInput();
        }

        Session::put('storefront_checkout_customer', [
            'customer_name' => (string) $request->input('customer_name'),
            'email' => (string) $request->input('email'),
            'phone' => (string) $request->input('phone'),
            'address' => (string) $request->input('address'),
            'city' => (string) $request->input('city'),
            'notes' => (string) $request->input('notes'),
            'grand_total' => $summary['grand_total'],
            'currency' => 'QAR',
            'reference' => $reference,
        ]);

        Session::put('storefront_paypal_order_id', $order['id']);

        return redirect()->away($order['approve_url']);
    }

    public function paypalReturn(Request $request, PayPalService $paypal): View|RedirectResponse
    {
        $orderId = (string) ($request->input('token') ?: Session::get('storefront_paypal_order_id', ''));

        if ($orderId === '') {
            return redirect()->route('storefront.checkout')->withErrors([
                'paypal' => 'PayPal session expired. Please try again.',
            ]);
        }

        $capture = $paypal->captureOrder($orderId);

        if (! $capture) {
            return redirect()->route('storefront.checkout')->withErrors([
                'paypal' => 'PayPal payment could not be completed. No charge was made. Please try again.',
            ]);
        }

        $customer = Session::get('storefront_checkout_customer', []);
        $summary = $this->cartSummary();

        $transactionId = data_get($capture, 'purchase_units.0.payments.captures.0.id', $orderId);

        Session::forget('storefront_cart');
        Session::forget('storefront_cart_sizes');
        Session::forget('storefront_checkout_customer');
        Session::forget('storefront_paypal_order_id');

        return view('storefront.success', [
            'cartCount' => 0,
            'customer' => $customer,
            'qpayPayload' => [],
            'summary' => $summary,
            'paypal' => [
                'transaction_id' => $transactionId,
                'status' => $capture['status'] ?? 'COMPLETED',
                'currency' => $paypal->currency(),
            ],
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function paypalCancel(): RedirectResponse
    {
        Session::forget('storefront_paypal_order_id');

        return redirect()->route('storefront.checkout')->withErrors([
            'paypal' => 'PayPal payment was cancelled. You can review your cart and try again.',
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $summary = $this->cartSummary();

        if ($summary['items']->isEmpty()) {
            return redirect()->route('storefront.index');
        }

        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:255'],
        ]);

        if (! $this->qpayConfigured()) {
            return redirect()->route('storefront.checkout')->withErrors([
                'qpay' => 'QPay credentials are missing. Please add QPAY_MERCHANT_ID and QPAY_SECRET in the .env file.',
            ])->withInput();
        }

        $orderDraft = [
            'customer_name' => (string) $request->input('customer_name'),
            'email' => (string) $request->input('email'),
            'phone' => (string) $request->input('phone'),
            'address' => (string) $request->input('address'),
            'city' => (string) $request->input('city'),
            'notes' => (string) $request->input('notes'),
            'grand_total' => $summary['grand_total'],
            'currency' => config('services.qpay.currency', 'qar'),
        ];

        Session::put('storefront_checkout_customer', $orderDraft);
        $payload = $this->qpayPayload($summary['items'], $summary['shipping'], $orderDraft);

        Session::put('storefront_qpay_payload', $payload);

        return redirect()->route('storefront.checkout.success')->with('success', 'QPay payment request prepared. Complete the payment from the secure gateway flow.');
    }

    public function checkoutSuccess(Request $request): View|RedirectResponse
    {
        $qpayPayload = Session::get('storefront_qpay_payload', []);
        $summary = $this->cartSummary();
        $customer = Session::get('storefront_checkout_customer', []);
        Session::forget('storefront_cart');
        Session::forget('storefront_cart_sizes');
        Session::forget('storefront_checkout_customer');
        Session::forget('storefront_qpay_payload');

        return view('storefront.success', [
            'cartCount' => 0,
            'customer' => $customer,
            'qpayPayload' => $qpayPayload,
            'summary' => $summary,
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }

    public function checkoutCancel(): RedirectResponse
    {
        return redirect()->route('storefront.checkout')->withErrors([
            'qpay' => 'QPay payment was cancelled. You can review the cart and try again.',
        ]);
    }

    public function page(string $slug): View
    {
        $page = $this->cmsPage($slug);
        abort_if(! $page, 404);

        return view('storefront.page', [
            'page' => $page,
            'cartCount' => (int) collect($this->getCartMap())->sum(),
            'cmsSetting' => $this->cmsSetting(),
        ]);
    }
}
