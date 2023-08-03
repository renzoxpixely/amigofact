@extends('tenant.layouts.app')

@section('content')

    <tenant-logistics-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{ json_encode($configuration) }}"
    ></tenant-logistics-index>

@endsection
