@extends('layouts.app')

@section('Personal information')
    Список всех домов
@endsection

@section('aside')
@endsection

@section('content')
    <h2>Количество домов: {{count($houses)}}</h2>
    @foreach($houses as $house)
        <div class="card border-dark mb-3" style="max-width: 75rem;">
            <div class="card-header">
                <a href="{{route('house_show', $house->id)}}"><h1 class="card-title">Дом {{$house->name}}</h1></a>
                <div class="col-8">
                    <img src="{{Storage::url('public/image/thumbnail/'.$house->getPhoto())}}">
                </div>
                
                @foreach($house->dates->unique() as $date)
                    <tr>
                    <th scope="row">{{$date->getWeek()['start']}}-{{$date->getWeek()['end']}}</th>
                    @if ($house->getPrices($date->week_start)["weekday_price"]===0)<td>не установлена</td>                   
                    @else <td>{{$house->getPrices($date->week_start)["weekday_price"]}} руб.</td>
                    @endif
                    @if ($house->getPrices($date->week_start)["weekend_price"]===0)<td>не установлена</td>                   
                    @else <td>{{$house->getPrices($date->week_start)["weekend_price"]}} руб.</td>
                    @endif                
                    </tr>
                @endforeach

                <h2 class="card-title">От {{$house->getMinimalPrice()}} рублей</h2>
            </div>
            <div class="card-body text-dark">
                <h3>Спальных мест: {{$house->beds_count}}</h3>
                @if($house->has_electricity)
                    <h3>Есть электричество</h3>
                    @endif
                @if($house->has_shower)
                    <h3>Есть собственный душ и туалет</h3>
                @endif
                <form action="{{ route('add', $house->id) }}">
                    <div class="col">
                        <input type="submit" class="btn btn-info w-100" value="Бронировать">
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
