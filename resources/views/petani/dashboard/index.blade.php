@extends('layouts.dashboard.app')

@section('title','Dashboard')
@section('MenudashboardActive','active')

@section('content')
    @livewire('petani.dashboard.index')
@endsection