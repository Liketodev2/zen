<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\SiteInfo;

class AdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $plansCount = Plan::count();
        $usersCount = User::count();
        $info = SiteInfo::first() ?? new SiteInfo();
        $transactionsCount = Transaction::count();
        return view('admin.dashboard', compact('plansCount', 'usersCount', 'info', 'transactionsCount'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function transactions()
    {
        $transactions = Transaction::query()->with(['user', 'taxReturn'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.transactions.index', compact('transactions'));
    }
}
