@extends('layouts.app')

@section('header')
    @include('include.header')
@endsection

@section('content')
    <div id="app">
        <todo></todo>
    </div>
@endsection