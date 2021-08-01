@extends('layouts.app')

@section('content')
    <h1>Бронь</h1>
    <div class="scrollable">
    <table class="table table-bordered text-center">
        <thread>
                <tr>
                    <th scope="col"> Домики </th>
                    @foreach($dates as $date)
                        <th scope="col" >{{$date->dayOfMonth }}&nbsp{{$date->month}}<br>{{$date->dayOfWeek}}</th>
                    @endforeach

                </tr>
        </thread>
        <tbody>
        @foreach($houses as $house)
            <tr>
                <th scope="row">Домик<br><a href="{{route('house_show',$house->id)}}">{{$house->name}}</a></th>
                @foreach($schedule[$house->name] as $scheduleByDay)
                    <td colspan={{$scheduleByDay[0]}}> <a href="{{ route('add',['id'=>$scheduleByDay[1],'date'=>$scheduleByDay[3]])}}">{{$scheduleByDay[2]}}</a> </td>
                @endforeach
            </tr>
        @endforeach

        </tbody>
    </table>
    </div>
@endsection
