<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Carbon\Carbon;
use App\Http\Requests\SendReviewRequest;
use App\Events\OrderReviewed;
use App\Http\Requests\ApplyRefundRequest;
use App\Exceptions\CouponCodeUnavailableException;
use App\Models\CouponCode;

class OrdersController extends Controller
{
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::find($request->input('address_id'));
        $coupon  = null;

        // If the user submitted a coupon code
        if ($code = $request->input('coupon_code')) {
            $coupon = CouponCode::where('code', $code)->first();
            if (!$coupon) {
                throw new CouponCodeUnavailableException('Counpon code not exist');
            }
        }
        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'), $coupon);
    }
    public function index(Request $request)
    {
        $orders = Order::query()

            ->with(['items.product', 'items.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    public function received(Order $order, Request $request)
    {
        // Check permission
        $this->authorize('own', $order);

        // Determine whether the delivery status of the order is shipped
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
            throw new InvalidRequestException('Delivery status is incorrect');
        }

        // Update shipping status as received
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

        // Return order information
        return $order;
    }

    public function review(Order $order)
    {
        // Check permission
        $this->authorize('own', $order);
        // Determine if the order has been paid
        if (!$order->paid_at) {
            throw new InvalidRequestException('The order was not paid yet, cannot leave a comment');
        }
        // Use the load method to load associated data to avoid N + 1 performance issues
        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    public function sendReview(Order $order, SendReviewRequest $request)
    {

        $this->authorize('own', $order);

        if (!$order->paid_at) {
            throw new InvalidRequestException('The order was not paid yet, cannot leave a comment');
        }
        // Determine if the order has a reveiw already
        if ($order->reviewed) {
            throw new InvalidRequestException('The order has been reviewed and cannot be submitted repeatedly');
        }
        $reviews = $request->input('reviews');
        // Open transaction
        \DB::transaction(function () use ($reviews, $order) {
            // foreach user submitted data
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                // Save ratings and reviews
                $orderItem->update([
                    'rating'      => $review['rating'],
                    'review'      => $review['review'],
                    'reviewed_at' => Carbon::now(),
                ]);
            }
            // Mark an order as reviewed
            $order->update(['reviewed' => true]);

            event(new OrderReviewed($order));

        });

        return redirect()->back();
    }

    public function applyRefund(Order $order, ApplyRefundRequest $request)
    {
        // Verify if order belongs to the current user
        $this->authorize('own', $order);
        // Determine if the order has been paid
        if (!$order->paid_at) {
            throw new InvalidRequestException('This order is unable to apply for a refund unless is paid');
        }
        // Determine if the order refund status is correct
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new InvalidRequestException('The order has been applied for a refund. Please do not repeat the application.');
        }
        // Put the reason for the refund entered by the user into the extra field of the order
        $extra                  = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');
        // Change order refund status to requested refund
        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);

        return $order;
    }

}
