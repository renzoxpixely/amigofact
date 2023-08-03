@extends('tenant.layouts.app')

@section('content')
    <tenant-commercials-participacion-form :type-user="{{json_encode(Auth::user()->type)}}"
     :id="{{json_encode($id)}}"
     :quotation-id="{{json_encode($quotationId)}}"
     :show-payments="{{json_encode($showPayments)}}"
     ></tenant-commercials-participacion-form>
@endsection
