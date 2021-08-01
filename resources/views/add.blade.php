@extends('layouts.app')

@section('Personal information')
    Новый заказ
@endsection

@section('aside')

@endsection

@section('content')
    <div class="card border-dark mb-3" style="max-width: 100rem;">
        <div class="card-header">
    <h1 class="card-title">Оформление нового заказа</h1>
        </div>
        <div class="card-body text-dark">
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

    <form action="{{ route('addsubmit', $data->id)}}" method="post">
        @csrf
        <div class="form-group">
            <label for="house">Дом для бронирования:</label>
            <select name="order[house_id]" id="house" required>
                <option value="{{ $data->id }}">{{$data->name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="order[date_in]">Дата заезда:</label>
            <input type="date" name="order[date_in]" id="order[date_in]" min="{{$range["min"]}}"
                   max="{{ $range["max"] }}" value="{{$date}}" class="form-control" required>

        </div>
        <div class="form-group">
            <label for="order[date_out]">Дата выезда:</label>
            <input type="date" name="order[date_out]" id="order[date_out]" min="{{$range["min"]}}"
                   max="{{ $range["max"] }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="name">Ваше имя:</label>
            <input type="text" name="order[name]"  id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Номер телефона:</label>
            <input type="text" name="order[phone_number]"  id="phone_number" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Ваш E-Mail:</label>
            <input type="text" name="order[email]" id="email" class="form-control" required>
        </div>

        <a href="">Пользовательское соглашение</a>

        <div class="form-group">

        <input type="checkbox" name="personal" id="personal" value="isPersonal" required>
            <label for="personal">Я согласен с пользовательским соглашением</label>
        </div>

        @if(count($busyDays))
        <div class="form-group">
            <p>Домик занят в следующие дни:</p>
            @for($i = 0; $i < count($busyDays["start"]); $i++)
                <p>{{$busyDays["start"][$i]->dayOfMonth}} {{$busyDays["start"][$i]->month}}
                    - {{$busyDays["end"][$i]->dayOfMonth}} {{$busyDays["end"][$i]->month}}</p>
            @endfor
        </div>
        @endif
        <div class="col">
            <input type="submit" class="btn btn-info w-100" value="Бронировать">
        </div>
        <div class="col">
            <a href="{{route('show-form')}}" class="btn btn-primary w-100">Назад</a>
        </div>
    </form>
    </div>

    @endsection
