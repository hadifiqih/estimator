@extends('layouts.app')

@section('title', 'Unduh Laporan Workshop')

@section('username', Auth::user()->name)

@section('page', 'Report')

@section('breadcrumb', 'Laporan Workshop')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Laporan Workshop</h2>
                </div>
                <div class="card-body">
                    <label>Unduh Laporan</label>
                    <form action="{{ route('laporan-workshop-pdf') }}" method="POST" target="_blank">
                        @csrf
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                        </div>
                    </form>
                    <p class="mt-2 text-sm text-muted font-italic">*Hanya dapat mengunduh laporan dalam 1 hari</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
