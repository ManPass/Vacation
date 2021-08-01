@extends('layouts.app')

@section('title-block')
    Добавление дома
@endsection

@section('content')
<div class="login-page">
    <h1>Добавление нового дома</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('message'))
        <div class="alert alert-danger">
            {{session('message')}}
        </div>

    @endif

        <form class="login-form" action="{{ route('house-update-add')}}"  method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Название дома: </label>
                <input type="text" name="house[name]" id="name" class="form-control">
            </div>

            <div class="form-group">
                <label for="beds_count">Количество спальных мест: </label>
                <input type="number" name="house[beds_count]" id="beds_count" class="form-control">
            </div>

            <div class="form-group">
                <label for="has_electricity">Наличие электричества:</label>
                <input type="hidden" name="house[has_electricity]" value="0" id="has_electricity">
                <input type="checkbox" name="house[has_electricity]" value="1" id="has_electricity">
            </div>

            <div class="form-group">
                <label for="has_shower">Наличие водоснабжения:</label>
                <input type="hidden" name="house[has_shower]" value="0" id="has_shower">
                <input type="checkbox" name="house[has_shower]" value="1" id="has_shower">
            </div>

            <div class="form-group">
                <label for="description">Описание: </label>
                <textarea name="house[description]" id="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="photo">Изображение: </label>
                <input multiple="multiple" name="photos[]" type="file">
            </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{route('houses-view')}}" class="btn btn-secondary">Вернуться</a>

    </form>
    @endsection
