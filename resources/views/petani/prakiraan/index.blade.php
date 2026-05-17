@extends('layouts.dashboard.app')

@section('title','Prakiraan Cuaca')
@section('MenuprakiraanActive','active')

@section('content')
    @livewire('petani.prakiraan.index')
@endsection