@extends('layouts.app')

@section('content')

    @isset($id)
        <filebrowser server="{{$id}}" start="{{$start}}"/>
    @else
        <p> Error </p>
    @endisset

@endsection
