<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteInfo;
use App\Models\TaxReturn;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('index', compact('user'));
    }

    public function services()
    {
        $plan = \App\Models\Plan::with('options')->first();
        return view('pages.services', compact('plan'));
    }

    /**
     * Handle "Get Started" from services page.
     * If user is authenticated, redirect to existing incomplete form or to create a new one.
     * If not authenticated, redirect to login page.
     */
    public function startPlan(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $incompleteForm = TaxReturn::where('user_id', $user->id)
            ->where('form_status', 'incomplete')
            ->latest()
            ->first();

        if ($incompleteForm) {
            return redirect()->route('tax-returns.edit', $incompleteForm->id);
        }

        return redirect()->route('tax-returns.create');
    }

    public function contact()
    {

        $info = SiteInfo::first();
        return view('pages.contact', compact('info'));
    }

    public function privacyPolicy()
    {
        $info = SiteInfo::first();
        return view('pages.privacy-policy', compact('info'));
    }

    public function termsService()
    {
        $info = SiteInfo::first();
        return view('pages.terms-service', compact('info'));
    }


    public function success()
    {
        return view('pages.success');
    }
}
