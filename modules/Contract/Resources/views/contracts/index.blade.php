@extends('tenant.layouts.app')

@section('content')

    <a href="{{ route('tenant.contracts.items.report') }}" class="btn btn-success mt-3 mb-3">Descargar</a>
    <a href="{{ route('tenant.contracts.items.reportview') }}" class="btn btn-info mt-3 ml-3">Ver</a>
    <tenant-contracts-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{ json_encode($configuration) }}"
    ></tenant-contracts-index>

@endsection
