@extends('layouts.app')

@section('Images')
    Тест изображений
@endsection

@section('content')
   <div class="container">
       <div class="col-8">
           <h1>Добавить изображение</h1>
           <form method="post" action="{{route('insert_photo')}}" enctype="multipart/form-data">
               @csrf
               <div class="form-group">
                   <label for="name">Название домика</label>
                   <input type="text" class="form-control" id="name" name="photo[house_name]">
               </div>
               <div class="form-group">
                   <label for="photo">Фотография домика</label>
                   <input type="file" class="form-control-file" id="photo" name="photo[photo]">
               </div>
               <button type="submit" class="btn btn-primary">Добавить</button>
           </form>
       </div>
   </div>
    <div class="container">
        @foreach($data as $item)
            <div class="col-8">
                <h2>{{$item->house_id}}</h2>
                <p>{{Storage::url('public/image/thumbnail/'.$item->photo)}}</p>
                <img src="{{Storage::url('public/image/thumbnail/'.$item->photo)}}" alt="{{$item->photo}}">
            </div>
            @endforeach
    </div>
@endsection
