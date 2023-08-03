@extends('tenant.layouts.app')

@section('content')

    <tenant-purchases-import_purchase :purchase_order_id="{{ json_encode($purchase_order_id) }}"></tenant-purchases-import_purchase>

@endsection
