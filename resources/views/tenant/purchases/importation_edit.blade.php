@extends('tenant.layouts.app')

@section('content')

    <tenant-purchases-importation_edit :resource-id="{{json_encode($resourceId)}}"></tenant-purchases-importation_edit>

@endsection
