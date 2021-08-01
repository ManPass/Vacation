<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\House;
use App\Services\Date\DateService;
use App\Models\Order;
use App\Services\OrderService;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Добавление нового заказа
     * @param OrderRequest $req - данные с формы
     * @return Application|Factory|View|RedirectResponse
     */
    public function addOrder(int $id, OrderRequest $req)
    {
        $house = House::find($id);
        if($house->isFreeOn($req->order["date_in"],$req->order["date_out"]))
        {
            $state = OrderService::checkOrderState($house, $req->order["date_in"], $req->order["date_out"]);
            if(!$state["check"])
            {
                return redirect()->back()->with("message", "Ошибка в выполнении заказа, проверьте даты на корректность");
            }
            $order = Order::create($req->order);
            $house->orders()->save($order);

            return redirect()->back()->with("message", "Заказ успешно внесён! К оплате {$state["fullPrice"]} рублей");
        }
        return redirect()->back()->with("message", "Домик занят в данную дату!");
    }

    public function show(Request $request,$id)
    {
        $house = House::find($id);
        $date = request()->date;

        return view('add',
            ['data' => $house,
                'date' => $date,
                'range' => DateService::getDateRange(true),
            'busyDays' => $house->getBusyDaysAsDateUnit()]);
    }
}
