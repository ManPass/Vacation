@extends('layouts.app')

@section('title-block')
    Редактирование дома
@endsection

@section('content')
<div class="login-page">
    <h1>Изменение {{$data->name}}</h1>
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
        <div class="alert alert-success">
            {{session('message')}}
        </div>

    @endif

        <div class="form-group">
            <div class="col-8">
                @foreach($data->photos as $photo)
                    <form action="{{route('delete_photo', $photo->id)}}" method="get">
                    <img src="{{Storage::url('public/image/thumbnail/'.$photo->photo)}}">
                    <a href="{{route('delete_photo', $photo->id)}}">Удалить</a><br>
                    </form>
                @endforeach
            </div>
        </div>

        <form class="login-form" action="{{ route('house-update-changes', $data->id)}}"  method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                    <div class="form-group">
                        <label for="photo">Изображение:</label>
                        <input multiple="multiple" name="photos[]" type="file">
                    </div>
            </div>

            <div class="form-group">
                <label for="name">Название дома: </label>
                <input type="text" name="house[name]" value= "{{$data->name}}" id="name" class="form-control">
            </div>

            <div class="form-group">
                <label for="beds_count">Количество спальных мест: </label>
                <input type="number" name="house[beds_count]" value="{{$data->beds_count}}" id="beds_count" class="form-control">
            </div>

            <div class="form-group">
                <label for="has_electricity">Наличие электричества:</label>
                @if ($data->has_electricity===0)
                    <input type="hidden" name="house[has_electricity]" value="0" id="has_electricity">
                    <input type="checkbox" name="house[has_electricity]" value="1" id="has_electricity">
                @else
                    <input type="hidden" name="house[has_electricity]" value="0" id="has_electricity">
                    <input type="checkbox" input checked="checked" name="house[has_electricity]" value="1" id="has_electricity">
                @endif
            </div>

            <div class="form-group">
                <label for="has_shower">Наличие водоснабжения:</label>
                @if ($data->has_shower===0)
                    <input type="hidden" name="house[has_shower]" value="0" id="has_shower">
                    <input type="checkbox" name="house[has_shower]" value="1" id="has_shower">
                @else
                    <input type="hidden" name="house[has_shower]" value="0" id="has_shower">
                    <input type="checkbox" input checked="checked" name="house[has_shower]" value="1" id="has_shower">
                @endif
            </div>

            <div class="form-group">
                <label for="description">Описание: </label>
                <textarea name="house[description]" value="{{$data->description}}" id="description" class="form-control">{{$data->description}}</textarea>
            </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{route('houses-view')}}" class="btn btn-secondary">Вернуться</a>

    </form>
    @endsection
