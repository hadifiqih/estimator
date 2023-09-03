@extends('layouts.app')

@section('title', 'Edit Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Edit Antrian')

@section('content')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title">Edit Antrian #{{ $antrian->ticket_order }}</h2>
            </div>
            <div class="card-body">
    <form action="{{ route('antrian.update', $antrian->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row ml-1">
      <h6>Pilih Operator :</h6>
      @if($employees != null)
      @foreach($employees as $employee)
      <div class="col-sm">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="operator{{ $employee->id }}" name="operator" value="{{ $employee->id }}" {{ $employee->id == $antrian->operator_id ? 'checked' : '' }}>
            <label for="operator{{ $employee->id }}" class="form-check-label">{{ $employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="operatorRekanan" name="operator" value="rekanan" {{ $antrian->operator_id == 'rekanan' ? 'checked' : '' }}>
          <label for="operatorRekanan" class="form-check-label">Rekanan</label>
        </div>
      </div>
    </div>
    @else
    -
    @endif
    <hr>


    <div class="row ml-1">
      <h6>Pilih Finishing :</h6>
      @if($employees != null)
      @foreach($employees as $employee)
      <div class="col-sm">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="finishing{{ $employee->id }}" name="finisher" value="{{ $employee->id }}" {{ $employee->id == $antrian->finisher_id ? 'checked' : '' }}>
            <label for="finishing{{ $employee->id }}" class="form-check-label">{{ $employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="finishingRekanan" name="finisher" value="rekanan" {{ $antrian->finisher_id == 'rekanan' ? 'checked' : '' }}>
          <label for="finishingRekanan" class="form-check-label">Rekanan</label>
        </div>
      </div>
    </div>
    @else
    -
    @endif
    <hr>

    <div class="row ml-1">
      <h6>Pilih QC : </h6>
      @if($qualitys != null)
      @foreach($qualitys as $quality)
      <div class="col-sm">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="quality{{ $quality->id }}" name="quality" value="{{ $quality->id }}" {{ $quality->id == $antrian->qc_id ? 'checked' : '' }}>
          <label for="quality{{ $quality->id }}" class="form-check-label">{{ $quality->name }}</label>
      </div>
      </div>
      @endforeach
    </div>
    @else
    -
    @endif
    <hr>

    {{-- Memilih tempat pengerjaan di Surabaya, Kediri, Malang --}}
    <div class="mb-3">
        <label for="tempat" class="form-label">Tempat : </label>
        <select class="custom-select rounded-2" id="tempat" name="tempat">
            <option value="Surabaya" {{ $antrian->working_at == 'Surabaya' ? 'selected' : ''}}>Surabaya</option>
            <option value="Kediri" {{ $antrian->working_at == 'Kediri' ? 'selected' : ''}}>Kediri</option>
            <option value="Malang" {{ $antrian->working_at == 'Malang' ? 'selected' : ''}}>Malang</option>
        </select>
    </div>

    <div class="mb-3">
        {{-- Masukkan Deadline --}}
        <label for="deadline" class="form-label">Deadline</label>
        <input type="datetime-local" class="form-control" id="deadline" aria-describedby="deadline" name="deadline" value="{{ $antrian->end_job }}">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
</div>
</div>
</div>
</div>
@endsection
