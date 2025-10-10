<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxReturnResource;
use App\Models\TaxReturn;
use Illuminate\Http\Request;

class TaxReturnController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $taxReturns = TaxReturn::query()
            ->where('user_id', $request->user()->id)
            ->get();
        return TaxReturnResource::collection($taxReturns);
    }


    /**
     * @param Request $request
     * @param $id
     * @return TaxReturnResource
     */
    public function show(Request $request)
    {
        $userId = $request->user()->id;
        $taxReturn = TaxReturn::with(['basicInfo', 'income', 'deduction', 'other'])
        ->firstOrCreate(
            [
                'user_id' => $userId,
                'form_status' => 'incomplete',
            ],
            [
                'payment_status' => 'unpaid',
            ]
        );
        return new TaxReturnResource($taxReturn);
    }
}
