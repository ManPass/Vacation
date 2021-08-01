<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order.house_id' => 'required',
            'order.date_in' => 'required|date|after:yesterday',
            'order.date_out' => 'required|date|after:order.date_in',
            'order.name' => 'required|min:2|max:30',
            'order.phone_number' => 'required|digits:11',
            'order.email' => 'required|email'
        ];
    }

    public function messages(): array
    {
        return [
            'order.house_id.required' => 'Вы не выбрали домик для бронирования',
            'order.date_in.required' => 'Вы не выбрали дату въезда',
            'order.date_in.date' => 'Вы ввели дату в некорректном формате (пример: 21.03.2021)',
            'order.date_out.required' => 'Вы не выбрали дату выезда',
            'order.date_out.date' => 'Вы ввели дату в некорректном формате (пример: 21.03.2021)',
            'order.name.required' => 'Вы не ввели своё имя',
            'order.name.min' => 'Вы указали слишком короткую строку в поле "Ваше имя"',
            'order.name.max' => 'Вы указали слишком длинную строку в поле "Ваше имя"',
            'order.phone_number.required' => 'Номер телефона не был введён',
            'order.phone_number.digits' => 'Некорректный номер телефона. Допускатеся только числовой ввод.
            Если вы вводите домашний номер, используйте код страны, код города и т.п.',
            'order.email.email' => 'Вы неправильно набрали адрес E-Mail'
        ];
    }
}
