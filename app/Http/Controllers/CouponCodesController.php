<?php

namespace App\Http\Controllers;

use App\Exceptions\CouponCodeUnavailableException;
use App\Models\CouponCode;

class CouponCodesController extends Controller
{
    public function show($code)
    {
        if (!$record = CouponCode::where('code', $code)->first()) {
            throw new CouponCodeUnavailableException('coupon code not exist');
        }

        $record->checkAvailable();

        return $record;
    }
}
