@extends('layouts.app')

@section('header')
    @include('include.header')
@endsection

@section('content')
    <div id="app">
        <user-table></user-table>
    </div>
@endsection