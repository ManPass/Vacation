@extends('layouts.app')

@section('Personal information')
    Список всех домов
@endsection

@section('aside')
@endsection

@section('content')
        <div class="card border-dark mb-3" style="max-width: 75rem;">
            <div class="card-header">
                <h1 class="card-title">Дом {{$house->name}}</h1>
                <div class="row">
                <div class="col-9">
                    <a href="{{ route('add', $house->id) }}"><button class="btn btn-info">Бронировать</button></a> 
                </div>
                <div class="col-1">
                    <a href="{{route('house')}}"><button class="btn btn-secondary w-180">К списку домов</button></a>       
                </div>
            </div> 
                <hr>
                <div class="col-8">
                    @foreach($house->photos as $photo)
                    <img src="{{Storage::url('public/image/thumbnail/'.$photo->photo)}}">
                    @endforeach
                </div>
            </div>
            <div class="card-body text-dark">
                <h3>Описание дома</h3>
                <p>{{$house->description }}</p>
            </div>
            <div class="card-body text-dark">
                Время заезда: с 16-00.<br>
                Время выезда: до 15-00.<br>
                Бесплатная услуга раннего заезда.<br>
                Дети до 6 лет размещаются бесплатно.<br>
            </div>

            <table class="table">
            <thead>
                <tr>
                <th scope="col">Дата
                    <p>недели</p>
                </th>
                <th scope="col">Стоимость в будни
                    <p>(заезд с вск по чт)</p>
                </th>
                <th scope="col">Стоимость в выходные 
                    <p>(заезд в пятницу и субботу)</p>
                </th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
            </table>
        </div>
@endsection
