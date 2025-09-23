<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('options')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $plan = Plan::create($request->only('name', 'description', 'price'));

        foreach ($request->options ?? [] as $option) {
            $plan->options()->create($option);
        }

        return redirect()->route('plans.index')->with('success', 'The plan is created.');
    }

    public function show(Plan $plan)
    {
        $plan->load('options');
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        $plan->load('options');
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($request->only('name', 'description', 'price'));

        $plan->options()->delete(); // clear old ones
        foreach ($request->options ?? [] as $option) {
            $plan->options()->create($option);
        }

        return redirect()->route('plans.index')->with('success', 'The plan has been updated.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'The plan has been removed.');
    }
}
