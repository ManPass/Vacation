<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseRequest;
use App\Models\House;
use App\Models\Photo;
use App\Services\PhotoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class PhotoController extends Controller
{
    public function index()
    {
        return view('test-image', ['data' => Photo::all()]);
    }

    /**
     * Добавляет изображение в БД и прикрепляет его к выбранному дому
     * @param House $house - дом для которого добавляется изображение
     * @param HouseRequest $request - запрос, хранящий изображение
     * @return RedirectResponse - перенаправление на страницу добавления
     */
    public static function insertPhoto(House $house, HouseRequest $request)
    {
        foreach($request->photos as $photo)
        {
            $tempPhoto = Photo::create(PhotoService::getFilenameFromStorage($house, $photo));
            $house->photos()->save($tempPhoto);
        }
    }

    public static function deletePhoto(int $id)
    {
        $photo = Photo::find($id);

        Storage::delete(['public/image/thumbnail/'.$photo->photo, 'public/image/origin/'.$photo->photo]);

        $photo->delete();

        return redirect()->back()->with('message', 'Изображение успешно удалено!');
    }
}
