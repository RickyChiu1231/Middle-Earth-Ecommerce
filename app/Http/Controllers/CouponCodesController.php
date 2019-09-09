<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Carbon\Carbon;

class CouponCodesController extends Controller
{
   public function show($code)
    {
        // Check if the coupon exist
        if (!$record = CouponCode::where('code', $code)->first()) {
            abort(404);
        }

        if (!$record->enabled) {
            abort(404);
        }

        if ($record->total - $record->used <= 0) {
            return response()->json(['msg' => 'This coupon has been redeemed'], 403);
        }

        if ($record->not_before && $record->not_before->gt(Carbon::now())) {
            return response()->json(['msg' => 'This coupon is not yet available'], 403);
        }

        if ($record->not_after && $record->not_after->lt(Carbon::now())) {
            return response()->json(['msg' => 'This coupon has expired'], 403);
        }

        return $record;
    }
}
