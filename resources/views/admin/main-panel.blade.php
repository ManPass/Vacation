@extends('layouts.app')

@section('title-block')
    Главная панель
@endsection

@section('content')
    <h1>Главная панель</h1>

    <div class="btn-group-vertical" role="group" >
        <h4>Панель перехода</h1>
        <a href="{{ route('houses-view')}}" class="btn btn-success">К редактированию домов</button></a>
        <a href="{{ route('show-form')}}" class="btn btn-success">На главную страницу</button></a>
        <a href="" class="btn btn-success">Куда-нибудь ещё</button></a> 
    </div>


    
 @endsection
