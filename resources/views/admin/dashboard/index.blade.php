@extends('layouts.dashboardadmin.app')

@section('title','Dashboard')
@section('MenudashboardActive','active')

@section('content')
    @livewire('admin.dashboard.index')
@endsection