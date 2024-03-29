<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StripeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

		return [
            'order_id'  => [
                empty(request('parcel_id')) ? 'required' : 'nullable',
                Rule::exists('orders', 'id')
            ],
            'parcel_id'  => [
                empty(request('order_id')) ? 'required' : 'nullable',
                Rule::exists('parcel_orders', 'id')
            ],
        ];
    }

}
