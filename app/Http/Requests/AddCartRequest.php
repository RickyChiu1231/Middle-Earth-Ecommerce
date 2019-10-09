<?php

namespace App\Http\Requests;

use App\Models\ProductSku;

class AddCartRequest extends Request
{
    public function rules()
    {
        return [
            'sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('This product does not exist');
                    }
                    if (!$sku->product->on_sale) {
                        return $fail('This product is not on sell');
                    }
                    if ($sku->stock === 0) {
                        return $fail('This product is sold out');
                    }
                    if ($this->input('amount') > 0 && $sku->stock < $this->input('amount')) {
                        return $fail('Insufficient inventory');
                    }
                },
            ],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes()
    {
        return [
            'amount' => 'product amount'
        ];
    }

    public function messages()
    {
        return [
            'sku_id.required' => 'Please select a product first'
        ];
    }
}
