@extends('layouts.app')

@section('title-block')
    Список домов
@endsection

@section('aside')

@endsection

@section('content')

        
    <h1>Список домов</h1>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="card-body text-dark">
                    @if(session('message'))
                        <div class="alert alert-success">
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
                </div>
            </div>
        </div>
            
        <div class="row">
            <div class="col-7">
                <a href="{{ route('admin-main-panel')}}"><button class="btn btn-secondary">Вернуться</button></a>
            </div>
            <div class="col-2">
                <a href="{{ route('admin-house-add')}}"><button class="btn btn-success">Добавить</button></a>           
            </div>
        </div>      
        
        @foreach($houses as $house)
            <div class="row">
                <div class="col-8"> 
                <hr> 
                    <form method="get" action="{{ route('admin-house-edit', $house->id) }}">
                        <div class="alert alert-info">
                            
                            <h3>Дом {{$house->name}}</h3>
                            @if($house->photos != null)
                                <h4>Кол-во фотографий: {{count($house->photos)}}</h4>
                            @else
                                <h4>Кол-во фотографий: 0</h4>
                            @endif
                            <h4>Количество спальных мест: {{$house->beds_count}}</h4>
                            @if ($house->has_electricity===1)
                                <h4>Электричество присутствует </h4>
                            @else <h4>Электричество отсутствует</h4>
                            @endif

                            @if ($house->has_shower===1)
                                <h4>Водоснабжение присутствует </h4>
                            @else <h4>Водоснабжение отсутствует</h4>
                            @endif

                            <h5>Описание: {{$house->description}}</h5>
                            <hr>
                            <div class="inner-input-container">
                                <div class="inner-input-line">
                                    <div class="item"><button type="submit" class="btn btn-success">Редактировать</button></div>
                                    <div class="item"><a class="btn btn-success" href="{{route('schedule-of-dates',['id'=>$house->id])}}">Рассписание</a></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    
    

    </div>
 @endsection
