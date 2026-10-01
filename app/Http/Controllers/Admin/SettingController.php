<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function brand()
    {
        $settings = Setting::where('group', 'brand')->pluck('value', 'key');

        return view('admin.settings.brand', compact('settings'));
    }

    public function platform()
    {
        $settings = Setting::where('group', 'platform')->pluck('value', 'key');

        return view('admin.settings.platform', compact('settings'));
    }

    public function privacyPolicy()
    {
        $settings = Setting::where('group', 'policy')->pluck('value', 'key');

        return view('admin.settings.privacy-policy', compact('settings'));
    }

    public function termsConditions()
    {
        $settings = Setting::where('group', 'terms')->pluck('value', 'key');

        return view('admin.settings.terms', compact('settings'));
    }

    public function contact()
    {
        $settings = Setting::where('group', 'contact')->pluck('value', 'key');

        return view('admin.settings.contact', compact('settings'));
    }

    public function faq(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $query->where('question', 'like', '%'.$request->search.'%')
                ->orWhere('answer', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category') && $request->category !== 'All Categories') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'All Status') {
            $query->where('status', $request->status);
        }

        $faqs = $query->orderBy('sort_order')->orderBy('id')->paginate(10)->withQueryString();

        return view('admin.settings.faq', compact('faqs'));
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $maxOrder = Faq::max('sort_order') ?? 0;
        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? ($maxOrder + 1),
            'is_active' => $request->status === 'Published',
        ]);

        return back()->with('success', 'FAQ added successfully.');
    }

    public function faqUpdate(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? $faq->sort_order,
            'is_active' => $request->status === 'Published',
        ]);

        return back()->with('success', 'FAQ updated successfully.');
    }

    public function faqDestroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted.');
    }

    public function index()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        $action = $request->input('action');
        $inputs = $request->except(['_token', '_method', 'group', 'action']);

        // Validate brand-specific fields
        if ($group === 'brand') {
            $request->validate([
                'brand_name' => 'required|string|max:100',
                'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'logo' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:2048',
                'favicon' => 'nullable|file|mimes:ico,png|max:512',
            ], [
                'primary_color.regex' => 'Primary color must be a valid hex color (e.g. #B00000).',
                'secondary_color.regex' => 'Secondary color must be a valid hex color (e.g. #FFFFFF).',
            ]);
        }

        if (in_array($group, ['policy', 'terms'])) {
            $prefix = $group === 'policy' ? 'privacy_policy' : 'terms_conditions';
            $currentVersion = Setting::where('group', $group)->where('key', "{$prefix}_version")->value('value') ?? '1.0';

            Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_last_updated"], ['value' => now()->toDateTimeString()]);

            if ($action === 'publish') {
                Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_status"], ['value' => 'Published']);
                Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_published_by"], ['value' => auth()->user()->name]);
                Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_published_on"], ['value' => now()->toDateTimeString()]);

                // Bump version if it was already published, or just set it
                $currentStatus = Setting::where('group', $group)->where('key', "{$prefix}_status")->value('value');
                if ($currentStatus === 'Published' && Setting::where('group', $group)->where('key', "{$prefix}_content")->value('value') !== ($inputs["{$prefix}_content"] ?? '')) {
                    $parts = explode('.', $currentVersion);
                    $newVersion = isset($parts[0], $parts[1]) ? $parts[0].'.'.($parts[1] + 1) : '1.1';
                    Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_version"], ['value' => $newVersion]);
                } else {
                    Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_version"], ['value' => $currentVersion]);
                }
            } elseif ($action === 'draft') {
                Setting::updateOrCreate(['group' => $group, 'key' => "{$prefix}_status"], ['value' => 'Draft']);
            }
        }

        foreach ($inputs as $key => $value) {
            if ($request->hasFile($key)) {
                // Saved under public/uploads so the live host can serve it (see App\Support\Media).
                $value = '/'.\App\Support\Media::store($request->file($key), 'settings');
            }

            if ($value !== null) {
                Setting::updateOrCreate(
                    ['group' => $group, 'key' => $key],
                    ['value' => $value, 'type' => is_numeric($value) ? 'number' : 'string']
                );
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
