<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incompleteForm = TaxReturn::where('user_id', auth()->id())
            ->where('form_status', 'incomplete')
            ->latest()
            ->first();

        $completedForms = TaxReturn::where('user_id', auth()->id())
            ->where('form_status', 'complete')
            ->latest()
            ->get();

        return view('pages.tax-returns.index', compact('incompleteForm', 'completedForms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = TaxReturn::where('user_id', auth()->id())
            ->where('form_status', 'incomplete')
            ->first();

        if($check) {
            return redirect()->route('tax-returns.edit', $check->id);
        }

        $taxReturn = TaxReturn::create([
            'user_id' => auth()->id(),
            'form_status' => 'incomplete',
            'payment_status' => 'unpaid',
        ]);
        return redirect()->route('tax-returns.edit', $taxReturn->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxReturn $taxReturn)
    {
        if($taxReturn->user_id !== auth()->id()) {
            abort(404);
        }

        $basicInfo = $taxReturn->basicInfo()->first();
        $incomes = $taxReturn->income()->first();
        $deductions = $taxReturn->deduction()->first();
        $other = $taxReturn->other()->first();
        return view('pages.tax-returns.create', compact('taxReturn', 'basicInfo', 'incomes', 'deductions', 'other'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxReturn $taxReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxReturn $taxReturn)
    {
        //
    }
}
