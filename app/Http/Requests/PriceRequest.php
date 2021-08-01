<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'weekday_price'=> 'integer|nullable|min:0|max:10000000',
            'weekend_price'=>'integer|nullable|min:0|max:10000000'
        ];
    }
    public function messages()
    {
        return [
            'weekday_price.integer'=>'Цена должна быть целочисленным',
            'weekday_price.min' => 'Цена не может быть меньше 1',
            'weekday_price.maч' => 'Цена не может быть больше 10000000',
            'weekend_price.integer'=>'Цена должна быть целочисленным',
            'weekend_price.min' => 'Цена не может быть меньше 1',
            'weekend_price.max' => 'Цена не может быть больше 10000000'
        ];
    }
}
