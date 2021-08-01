<?php


namespace App\Services;


use App\Http\Requests\HouseRequest;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotoService
{
    /**
     * Путь к папке с оригинальными изображениями
     * @var string
     */
    private static string $originPath;

    /**
     * Путь к папке с эскизами изображений
     * @var string
     */
    private static string $thumbnailPath;

    /**
     * Делает эскиз нового изображения и помещает его и его эскиз в папку-хранилище
     * @param House $house - модель для добавления изображения
     * @param $photo - изображение
     * @return array - массив[id дома, имя изображения]
     */
    public static function getFilenameFromStorage(House $house, $photo): array
    {
        //Указать путь до хранилища, где хранятся оригиналы изображений
        self::$originPath = Storage::path('public/image/').'origin/';

        //Указать путь до хранилища, где хранятся эскизы изображений
        self::$thumbnailPath = Storage::path('public/image').'/thumbnail/';

        //Сохранить информацию о новом изображении
        $data = $photo;
        //Получить само изображение из запроса
        $fileName = $data->getClientOriginalName();
        //Перенести его в папку-хранилище
        $data->move(self::$originPath, $fileName);
        //Сделать эскиз и перенести в хранилище
        self::makeThumbnail($fileName, 150, 150);

        //Вернуть данные, необходимые для добавления в БД
        return array('house_id' => $house->id, 'photo' => $fileName);
    }

    /**
     * Создаёт эскиз изображения
     * @param string $fileName - имя изображения
     * @param int $width - ширина будущего эскиза
     * @param int $height - высота будущего эскиза
     */
    private static function makeThumbnail(string $fileName, int $width, int $height)
    {
        $thumbnail = Image::make(self::$originPath.$fileName);
        $thumbnail->fit($width, $height);
        $thumbnail->save(self::$thumbnailPath.$fileName);
    }
}
