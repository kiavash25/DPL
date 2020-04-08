@extends('admin.layout.adminLayout')

@section('head')
    <style>
        .contentDiv{
            font-size: 23px;
            background: green;
            color: white;
            padding: 14px;
            border-radius: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-3">
            <a href="{{route('admin.destination.list')}}" style="display: inline-block">
                <div class="contentDiv">
                    <i class="fas fa-map-marker-alt" style="font-size: 40px; margin-right: 10px;"></i>
                    <span class="contentNum" style="margin-right: 10px;">
                        {{$destinationCount}}
                    </span>
                    Destination
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{route('admin.package.list')}}" style="display: inline-block">
                <div class="contentDiv">
                    <i class="fa fa-inbox" style="font-size: 40px; margin-right: 10px;"></i>
                    <span class="contentNum" style="margin-right: 10px;">
                        {{$packageCount}}
                    </span>
                    Packages
                </div>
            </a>
        </div>

        <div class="col-md-3">

            <a href="{{route('admin.journal.list')}}" style="display: inline-block">
                <div class="contentDiv">
                    <i class="fa fa-book" style="font-size: 40px; margin-right: 10px;"></i>
                    <span class="contentNum" style="margin-right: 10px;">
                        {{$journalCount}}
                    </span>
                    Journal
                </div>
            </a>
        </div>
    </div>

@endsection

@section('script')

@endsection

