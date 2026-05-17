@extends('layouts.dashboard.app')

@section('title','Data Padi Saya')
@section('MenupadisayaActive','active')

@section('content')
    @livewire('petani.padi.index')
@endsection