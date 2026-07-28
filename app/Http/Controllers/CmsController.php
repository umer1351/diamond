<?php

namespace App\Http\Controllers;

use App\Models\CmsHomeSection;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        return view('cms.index');
    }

    public function settings(): View
    {
        $setting = CmsSetting::first();
        return view('cms.settings', compact('setting'));
    }

    public function homepage(Request $request): View
    {
        $sections = CmsHomeSection::orderBy('sort_order')->get();
        $editingSection = $request->filled('section_id') ? $sections->firstWhere('id', (int) $request->input('section_id')) : null;
        return view('cms.homepage', compact('sections', 'editingSection'));
    }

    public function pages(Request $request): View
    {
        $pages = CmsPage::orderBy('title')->get();
        $editingPage = $request->filled('page_id') ? $pages->firstWhere('id', (int) $request->input('page_id')) : null;
        return view('cms.pages', compact('pages', 'editingPage'));
    }

    public function catalog(): View
    {
        return view('cms.catalog');
    }

    public function saveSetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'logo_text' => ['required', 'string', 'max:255'],
            'footer_about' => ['nullable', 'string'],
            'footer_email' => ['nullable', 'email', 'max:255'],
            'footer_phone' => ['nullable', 'string', 'max:255'],
            'footer_address' => ['nullable', 'string'],
            'contact_whatsapp' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'snapchat_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
        ]);

        CmsSetting::updateOrCreate(['id' => 1], $data + ['is_active' => 1]);

        return redirect()->route('cms.index')->with('success', 'CMS settings saved.');
    }

    public function saveSection(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'key' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm', 'max:25600'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $data['image_path'] = $file->store('cms', 'public');
            $data['media_type'] = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
            $data['media_url'] = null;
        }

        $section = !empty($data['id']) ? CmsHomeSection::find($data['id']) : new CmsHomeSection();
        $section->fill(collect($data)->except('id')->all() + ['is_active' => 1]);
        $section->save();

        return redirect()->route('cms.index')->with('success', 'Home section saved.');
    }

    public function savePage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'slug' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
        ]);

        $page = !empty($data['id']) ? CmsPage::find($data['id']) : new CmsPage();
        $page->fill(collect($data)->except('id')->all() + ['is_active' => 1]);
        $page->save();

        return redirect()->route('cms.index')->with('success', 'CMS page saved.');
    }

    public function deleteSection(CmsHomeSection $section): RedirectResponse
    {
        if ($section->image_path) {
            Storage::disk('public')->delete($section->image_path);
        }
        $section->delete();
        return redirect()->route('cms.index')->with('success', 'Home section deleted.');
    }

    public function deletePage(CmsPage $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('cms.index')->with('success', 'CMS page deleted.');
    }
}
