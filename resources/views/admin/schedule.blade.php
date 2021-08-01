@extends('layouts.app')

@section('content')

    <div class="container-fluid search-container date-container card border-dark mb-3">
        @if(session('message'))
            <div class="alert alert-danger">
                {{session('message')}}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-header">
            <h3> Дом {{$house['name']}} </h3> 
        </div>
        <hr>
        <div class="form-group">
            <a href="{{route('houses-view')}}" class="btn btn-secondary">Вернуться</a>
        </div>
        
        @foreach($dates as $date)
            <div class="alert alert-info search-element">
                <p class="fw-bold fs-3">Начало недели : {{$date->week_start}} </p>
                <div class="inner-text">
                    <p class="fw-bold fs-4">Цена за день: {{$date->weekday_price}} руб</p>
                    <p class="fw-bold fs-4">Цена за пят-субб: {{$date->weekend_price}} руб</p>
                </div>
                <div class="inputs">
                    <form action="{{route('update-price')}}" method="post">
                        @csrf
                        <input type="hidden" name="date_id" value={{$date->date_id}}>
                        <input type="hidden" name="house_id" value={{$house['id']}}>
                        <div class="inner-input-container">
                            <p>Новая цена за один день </p>
                            <div class="inner-input-line">
                                <div class="item"><div class="form-group"> <input type="text" class="form-control" name="weekday_price" id="weekday_price" aria-describedby="basic-addon1"></div></div>
                            </div>
                        </div>
                        <div class="inner-input-container">
                            <p>Новая цена за пят-субб</p>
                            <div class="inner-input-line">
                                    <div class="item"> <div class="form-group"> <input type="text" class="form-control" name="weekend_price" id="weekend_price" aria-describedby="basic-addon1"></div></div>
                            </div>
                            <button type="submit" class="btn btn-success"> Применить </button>
                        </div>

                    </form>
                </div>
            </div>

        @endforeach
    </div>

@endsection
