@extends('layouts.app')

@section('title', 'Edit Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'Antrian')

@section('breadcrumb', 'Edit Antrian')

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
    <form action="{{ route('antrian.update', $antrian->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row ml-1">
      <h6>Pilih Operator :</h6>
      @foreach($employees as $employee)
      <div class="col-sm">
        <div class="form-check">
            @if($employee->id == $antrian->operator_id)
            <input class="form-check-input" type="checkbox" id="operator{{ $employee->id }}" name="operator" value="{{ $employee->id }}" checked>
            @else
            <input class="form-check-input" type="checkbox" id="operator{{ $employee->id }}" name="operator" value="{{ $employee->id }}">
            @endif
            <label for="operator{{ $employee->id }}" class="form-check-label">{{ $employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          @if($rekanan->id == $antrian->operator_id)
          <input class="form-check-input" type="checkbox" id="operatorRekanan{{ $rekanan->id }}" name="operator" value="9999" checked>
          @else
          <input class="form-check-input" type="checkbox" id="operatorRekanan{{ $rekanan->id }}" name="operator" value="9999">
          @endif
          <label for="operatorRekanan{{ $rekanan->id }}" class="form-check-label">Rekanan</label>
        </div>
      </div>
    </div>
    <hr>
    

    <div class="row ml-1">
      <h6>Pilih Finishing :</h6>
      @foreach($employees as $employee)
      <div class="col-sm">
        <div class="form-check">
            @if($employee->id == $antrian->finisher_id)
            <input class="form-check-input" type="checkbox" id="finishing{{ $employee->id }}" name="finisher" value="{{ $employee->id }}" checked>
            @else
            <input class="form-check-input" type="checkbox" id="finishing{{ $employee->id }}" name="finisher" value="{{ $employee->id }}">
            @endif
            <label for="finishing{{ $employee->id }}" class="form-check-label">{{ $employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          @if($rekanan->id == $antrian->finisher_id)
          <input class="form-check-input" type="checkbox" id="finishingRekanan{{ $rekanan->id }}" name="finisher" value="9999" checked>
          @else
          <input class="form-check-input" type="checkbox" id="finishingRekanan{{ $rekanan->id }}" name="finisher" value="9999">
          @endif
          <label for="finishingRekanan{{ $rekanan->id }}" class="form-check-label">Rekanan</label>
        </div>
      </div>
    </div>
    <hr>

    <div class="row ml-1">
      <h6>Pilih QC : </h6>
      @foreach($qualitys as $quality)
      <div class="col-sm">
        <div class="form-check">
          @if($quality->id == $antrian->qc_id)
          <input class="form-check-input" type="checkbox" id="quality{{ $quality->id }}" name="quality" value="{{ $quality->id }}" checked>
          @else
          <input class="form-check-input" type="checkbox" id="quality{{ $quality->id }}" name="quality" value="{{ $quality->id }}">
          @endif
          <label for="quality{{ $quality->id }}" class="form-check-label">{{ $quality->name }}</label>
      </div>
      </div>
      @endforeach
    </div>
    <hr>

    {{-- Memilih tempat pengerjaan di Surabaya, Kediri, Malang --}}
    <div class="mb-3">
        <label for="tempat" class="form-label">Tempat : </label>
        <select class="custom-select rounded-2" id="tempat" name="tempat">
            <option value="Surabaya" @if($antrian->working_at == 'Surabaya') selected @endif>Surabaya</option>
            <option value="Kediri" @if($antrian->working_at == 'Kediri') selected @endif>Kediri</option>
            <option value="Malang" @if($antrian->working_at == 'Malang') selected @endif>Malang</option>
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
@endsection