<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;

class CouponCodeUnavailableException extends Exception
{
    public function __construct($message, int $code = 403)
    {
        parent::__construct($message, $code);
    }

    // When this exception is triggered, the rendering method is called to output to the user.
    public function render(Request $request)
    {
        //Returns a JSON-formatted error message if the user requests through Api
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->message], $this->code);
        }
        // Otherwise return to the previous page with an error message
        return redirect()->back()->withErrors(['coupon_code' => $this->message]);
    }
}
