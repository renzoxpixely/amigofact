@extends('tenant.layouts.app')

@section('content')

    <tenant-commercials-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{ json_encode($configuration) }}"
    ></tenant-commercials-index>

@endsection
