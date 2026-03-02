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

        $basicInfo = $taxReturn->basicInfo;
        $incomes = $taxReturn->income;
        $deductions = $taxReturn->deduction;
        $other = $taxReturn->other;
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


    /**
     * Check if the given tax return has any related data
     * (basic info, income, deduction or other).
     *
     * @param TaxReturn $taxReturn
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRelations(TaxReturn $taxReturn)
    {
        if ($taxReturn->user_id !== auth()->id()) {
            abort(404);
        }

        $taxReturn->load(['basicInfo', 'income', 'deduction', 'other']);

        $hasBasicInfo = $taxReturn->basicInfo !== null;
        $hasIncome = $taxReturn->income !== null;
        $hasDeduction = $taxReturn->deduction !== null;
        $hasOther = $taxReturn->other !== null;

        $hasAny = $hasBasicInfo || $hasIncome || $hasDeduction || $hasOther;

        return response()->json([
            'success' => true,
            'has_basic_info' => $hasBasicInfo,
            'has_income' => $hasIncome,
            'has_deduction' => $hasDeduction,
            'has_other' => $hasOther,
            'has_any' => $hasAny,
        ]);
    }
}
