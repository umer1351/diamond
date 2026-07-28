<?php

namespace Database\Seeders;

use App\Models\CmsHomeSection;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        CmsSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Azure Luxury',
                'logo_text' => 'AZURE',
                'footer_about' => 'Luxury jewellery and gift storefront powered by ERP data.',
                'footer_email' => 'info@azureluxury.com',
                'footer_phone' => '+97450903133',
                'footer_address' => 'Qatar',
                'contact_whatsapp' => '+97450903133',
                'facebook_url' => '#',
                'instagram_url' => '#',
                'youtube_url' => '#',
                'twitter_url' => '#',
                'copyright_text' => '2026 Azure Luxury - Qatar',
                'is_active' => 1,
            ]
        );

        $sections = [
            ['key' => 'hero_banner', 'title' => 'New Collection', 'subtitle' => 'Fresh Luxury', 'body' => 'Frontend ab live backend data se chal raha hai. Jo products ERP me add honge, woh isi storefront par stylish cards, detail page, aur cart flow ke saath show honge.', 'button_text' => 'Shop Now', 'button_url' => '#catalog', 'sort_order' => 1],
            ['key' => 'hero_video', 'title' => 'Homepage Video', 'subtitle' => 'Luxury Film', 'body' => null, 'button_text' => null, 'button_url' => 'https://www.youtube.com/watch?v=Xc0krzV1jbc', 'sort_order' => 2],
            ['key' => 'promo_banner', 'title' => 'Crafted gift sets and fine jewellery', 'subtitle' => 'Signature Edit', 'body' => 'Elegant presentation for premium products, inspired by your shared references.', 'button_text' => 'Open Cart', 'button_url' => '/storefront/cart', 'sort_order' => 3],
            ['key' => 'catalog_banner', 'title' => 'Shop by Category', 'subtitle' => 'Storefront Catalog', 'body' => null, 'button_text' => null, 'button_url' => null, 'sort_order' => 4],
            ['key' => 'latest_products', 'title' => 'Latest Pieces', 'subtitle' => 'New Arrival', 'body' => 'Freshly available products pulled from your current database.', 'sort_order' => 5],
            ['key' => 'editorial_drop', 'title' => 'Chain Collection', 'subtitle' => 'Editorial Drop', 'body' => 'Reference style ko preserve karte hue large editorial banner aur clean CTA section add kiya gaya hai.', 'button_text' => 'Shop Now', 'button_url' => '#gift-edit', 'sort_order' => 6],
            ['key' => 'gift_collection', 'title' => 'Gifts Collection', 'subtitle' => 'Gift Collection', 'body' => 'Uploaded reference images aur new products ko dedicated gift section me highlight kiya gaya hai.', 'button_text' => 'Go To Cart', 'button_url' => '/storefront/cart', 'sort_order' => 7],
            ['key' => 'feature_quality', 'title' => 'Elegant quality', 'sort_order' => 8],
            ['key' => 'feature_secure', 'title' => 'Secure shopping', 'sort_order' => 9],
            ['key' => 'feature_gift', 'title' => 'Gift-ready layout', 'sort_order' => 10],
            ['key' => 'feature_backend', 'title' => 'Backend linked', 'sort_order' => 11],
        ];

        foreach ($sections as $section) {
            CmsHomeSection::updateOrCreate(['key' => $section['key']], $section + ['is_active' => 1]);
        }

        foreach ([
            ['slug' => 'about-us', 'title' => 'About Us', 'meta_title' => 'About Azure Luxury', 'meta_description' => 'About the Azure Luxury brand.', 'body' => "Azure Luxury is a CMS-driven jewellery storefront powered by ERP inventory data."],
            ['slug' => 'privacy-policy', 'title' => 'Privacy and Security', 'meta_title' => 'Privacy and Security', 'meta_description' => 'Our privacy policy.', 'body' => "Your data is handled securely and only used for storefront and checkout processes."],
            ['slug' => 'shipping-returns', 'title' => 'Shipping & Returns', 'meta_title' => 'Shipping & Returns', 'meta_description' => 'Shipping and returns policy.', 'body' => "Orders are fulfilled based on the storefront checkout workflow configured by the admin."],
            ['slug' => 'terms-conditions', 'title' => 'Terms and Conditions', 'meta_title' => 'Terms and Conditions', 'meta_description' => 'Store terms and conditions.', 'body' => "These terms can be updated anytime from the CMS panel."],
        ] as $page) {
            CmsPage::updateOrCreate(['slug' => $page['slug']], $page + ['is_active' => 1]);
        }
    }
}
