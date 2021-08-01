<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends FormRequest
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


    public function rules()
    {
        $rules = [
            'house.name' => 'required|min:1|max:50',
            'house.beds_count' => 'required|min:1|max:10|',
            'house.has_electricity' => 'min:0|max:1|',
            'house.has_shower' => 'min:0|max:1|',
            'house.description' => 'required|max:255'
        ];
        if($this->file('photos') == null)
            return $rules;
        $photos = count($this->file('photos'));
        foreach(range(0, $photos) as $index)
        {
            $rules['photos.' . $index] = 'image|mimes:jpeg,bmp,png,jpg,gif|max:2048';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'house.name' => 'Название',
            'house.beds_count' => 'Количество спальных мест',
            'house.has_electricity' => 'Наличие электричества',
            'house.has_shower' => 'Наличие водоснабжения',
            'house.description' => 'Описание'
        ];
    }

    public function messages()
    {
        return [
            'house.name.required' => 'Поле "Название" является обязательным',
            'house.name.min' => '\"Название\" не может быть меньше 1 символа',
            'house.name.max' => '\"Название\" не может быть больше 50 символов',
            'house.beds_count.required' => 'Поле "Количество спальных мест" является обязательным',
            'house.beds_count.min' => '\"Количество спальных мест\" не может быть меньше 1',
            'house.beds_count.max' => '\"Количество спальных мест\" не может быть больше 10',
            'house.description.required' => 'Поле "Описание" является обязательным',
            'house.description.max' => 'Описание должно быть не больше 255 символов',
            'photo.required' => 'Изображение для дома не было добавлено',
            'photo.image' => 'Выбранный вами файл должен быть графическим файлом',
            'photo.mimes' => 'Файл изображения должен быть в формате: jpg, png, jpeg, gif',
            'photo.max' => 'Максимальный размер изображения не должен превышать 2 Мб'
        ];
    }
}
