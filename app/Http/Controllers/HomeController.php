<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteInfo;

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
        return view('pages.services');
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
