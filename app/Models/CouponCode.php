<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Exceptions\CouponCodeUnavailableException;

class CouponCode extends Model
{
    // Define the supported coupon types in a constant way
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED   => 'fixed amount',
        self::TYPE_PERCENT => 'percent',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    // Indicate that these two fields are date types
    protected $dates = ['not_before', 'not_after'];


    public static function findAvailableCode($length = 16)
    {
        do {
            // Generate a random string of the specified length and convert it to uppercase
            $code = strtoupper(Str::random($length));
        // Continue to loop if the generated code already exists
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    protected $appends = ['description'];

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0) {
            $str = '满'.str_replace('.00', '', $this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT) {
            return $str.'优惠'.str_replace('.00', '', $this->value).'%';
        }

        return $str.'减'.str_replace('.00', '', $this->value);
    }

    public function checkAvailable($orderAmount = null)
    {
        if (!$this->enabled) {
            throw new CouponCodeUnavailableException('CouponCode not exist');
        }

        if ($this->total - $this->used <= 0) {
            throw new CouponCodeUnavailableException('The coupon has been redeemed');
        }

        if ($this->not_before && $this->not_before->gt(Carbon::now())) {
            throw new CouponCodeUnavailableException('This coupon is not yet available');
        }

        if ($this->not_after && $this->not_after->lt(Carbon::now())) {
            throw new CouponCodeUnavailableException('This coupon has expired');
        }

        if (!is_null($orderAmount) && $orderAmount < $this->min_amount) {
            throw new CouponCodeUnavailableException('The order amount does not meet the minimum amount of the coupon');
        }
    }

    public function getAdjustedPrice($orderAmount)
    {
        // fixed amount
        if ($this->type === self::TYPE_FIXED) {

            return max(0.01, $orderAmount - $this->value);
        }

        return number_format($orderAmount * (100 - $this->value) / 100, 2, '.', '');
    }

    public function changeUsed($increase = true)
    {
        // Passing true means new usage, otherwise it is reducing usage
        if ($increase) {
            // Check if the current usage has exceeded the total amount.
            return $this->where('id', $this->id)->where('used', '<', $this->total)->increment('used');
        } else {
            return $this->decrement('used');
        }
    }
}
