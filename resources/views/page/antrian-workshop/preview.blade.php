@extends('layouts.app')

@section('title', 'Preview Dokumentasi')

@section('username', Auth::user()->name)

@section('page', 'Dokumentasi')

@section('breadcrumb', 'Preview Dokumentasi')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"></h2>
                </div>
                <div class="card-body">
                    {{-- Menampilkan Gambar dalam folder dokumentasi dalam bentuk carousel sesuai id yang dikirim melalui URL --}}
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($dokum as $item)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }} text-center">
                                <img class="img-fluid" src="{{ asset('storage/dokumentasi/'.$item->filename) }}" alt="First slide">
                                {{-- Tombol Download --}}
                                <a href="{{ asset('storage/dokumentasi/'.$item->filename) }}" class="btn btn-sm btn-primary mt-2" download>Download</a>
                            </div>
                            @endforeach
                        </div>
                        {{-- Tombol next dan previous --}}
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <i class="fas fa-chevron-left fa-2x"></i>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <i class="fas fa-chevron-right fa-2x"></i>
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
