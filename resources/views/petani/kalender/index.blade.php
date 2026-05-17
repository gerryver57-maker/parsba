@extends('layouts.dashboard.app')

@section('title','Kalender Manajemen')
@section('MenukalenderActive','active')

@section('content')
    @livewire('petani.kalender.index')
@endsection