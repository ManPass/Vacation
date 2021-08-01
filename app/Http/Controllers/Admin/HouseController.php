<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PhotoController;
use App\Models\Date;
use App\Models\House;
use App\Services\HouseService;
use App\Http\Requests\HouseRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
class HouseController extends Controller
{
    /**
     * Показывает представление со всеми домами
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('houses', ['houses' => House::getAll()]);
    }

    /**
     * Показывает представление с информацией о выбранном доме
     * @param $id - id дома
     * @return Application|Factory|View - представление
     */
    public function show(int $id)
    {
        return view('house_show', ['house' => HouseService::getHouse($id)]);
    }
/**
     * Добавление нового пароля
     * @param HouseRequest $req
     */
    public function showAllHouses()
    {
        return view('admin/houses-view', ['houses' => House::getAll()]);
    }

    public function editHouse($id)
    {
        return view('admin/house-edit', ['data' => HouseService::getHouse($id)]);
    }

    public function mainAdminPanel()
    {
        return view('admin/main-panel', ['houses' => House::getAll()]);
    }

    public function updateHouseAdding(HouseRequest $request)
    {
        if (HouseService::checkHouseName($request) == false){
            return redirect()->route('admin-house-add')->with('message','Дом с таким именем уже существует');
        }
        HouseService::addHouse($request);
        return redirect()->route('houses-view', ['data' => House::getAll()])->with('message','Данные о доме добавлены');
    }

    public function updateHouseChanges($id, HouseRequest $request)
    {
        $house = House::find($id);

        if ($house->name !== $request->input('house.name')){ //если при редактировании изменятся имя дома, то запускаем проверку на его оригинальность
            if (HouseService::checkHouseName($request) == false){
                return redirect()->route('house-edit',$id)->with('message','Дом с таким именем уже существует');
            }
        }

        if($request->photos)
        {
            PhotoController::insertPhoto($house, $request);
        }

        $house->update($request->house);
        return redirect()->route('houses-view', ['data' => House::getAll()])->with('message','Данные о доме изменены');
    }

}
