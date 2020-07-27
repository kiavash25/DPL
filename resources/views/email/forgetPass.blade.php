@extends('email.layout')

@section('head')

@endsection

@section('body')
    <div class="newPassword">
        <div>Hi: </div>
        <div>email: {{$userName}}</div>
        {{__('This is new password: ')}}
        <span style="font-weight: bold">{{$newPassword}}</span>
    </div>
@endsection
