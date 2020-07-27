@extends('email.layout')

@section('head')

@endsection

@section('body')
    <div class="newPassword">
        <div>Hi: {{$userName}}</div>
        {{__('New password: ')}}
        <span style="font-weight: bold">{{$newPassword}}</span>
        <div>
            <a href="http://discoverpersialand.com/loginPage/#singin">http://discoverpersialand.com/loginPage/#singin</a>
        </div>
    </div>
@endsection
