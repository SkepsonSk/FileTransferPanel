@extends('layouts.app')

@section('content')

    <dashboard-navigator user="{{$user}}"></dashboard-navigator>

    <servers></servers>

    @if( $power >= 1000 )
        <usermanager></usermanager>
    @endif

    @if( $power >= 100 )
        <serversmanager></serversmanager>
    @endif


@endsection
