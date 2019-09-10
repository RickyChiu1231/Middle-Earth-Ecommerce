<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\ProductSku;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;
use Carbon\Carbon;
use App\Models\CouponCode;
use App\Exceptions\CouponCodeUnavailableException;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items, CouponCode $coupon = null)
    {
        // If the coupon is passed in, first check if it is available
        if ($coupon) {
            // Because the total amount of the order has not yet been calculated, it will not be verified first.
            $coupon->checkAvailable();
        }

        // Create a database transaction
        $order = \DB::transaction(function () use ($user, $address, $remark, $items, $coupon) {
            // Update the last use time of the shipping address
            $address->update(['last_used_at' => Carbon::now()]);
            // Create a order
            $order   = new Order([
                'address'      => [ // Put the address into the order
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $remark,
                'total_amount' => 0,
            ]);
            // Link the order to the current user
            $order->user()->associate($user);
            // Save into the database
            $order->save();

            $totalAmount = 0;
            // Traversing user-submitted SKUs
            foreach ($items as $data) {
                $sku  = ProductSku::find($data['sku_id']);
                // Create an OrderItem and associate directly with the current order
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('Insufficient inventory');
                }
            }

            if ($coupon) {
                // The total amount has been calculated and checked for compliance with the coupon rules.
                $coupon->checkAvailable($totalAmount);
                // order amount should be change to the amount after using the coupon code has been applied.
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                // Link an order to a coupon
                $order->couponCode()->associate($coupon);
                // To increase the amount of coupons, you need to judge the return value.
                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('This coupon has been redeemed');
                }
            }

            // Update the total price of the order
            $order->update(['total_amount' => $totalAmount]);

            // Remove the ordered item from the shopping cart
            $skuIds = collect($items)->pluck('sku_id')->all();
            app(CartService::class)->remove($skuIds);

            return $order;
        });

        dispatch(new CloseOrder($order, config('app.order_ttl')));

        return $order;
    }
}
