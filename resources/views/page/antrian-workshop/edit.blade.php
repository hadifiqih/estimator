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
      @if($operators != null)
      @foreach($operators as $operator)
      <div class="col-sm">
        <div class="form-check">
            @php
                $isCheckedOperator = is_array($operatorId) && in_array($operator->employee->id, $operatorId);
                $rekananOperator = is_array($operatorId) && in_array('rekanan', $operatorId);
            @endphp
            <input class="form-check-input" type="checkbox" id="operator{{ $operator->employee->id }}" name="operator[]" value="{{ $operator->employee->id }}" {{ $isCheckedOperator ? 'checked' : '' }}>
            <label for="operator{{ $operator->employee->id }}" class="form-check-label">{{ $operator->employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="operatorRekanan" name="operator[]" value="rekanan" {{ $rekananOperator ? 'checked' : '' }}>
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
      @if($operators != null)
      @foreach($operators as $finishing)
      <div class="col-sm">
        <div class="form-check">
            @php
                $isCheckedFinishing = is_array($finisherId) && in_array($finishing->employee->id, $finisherId);
                $rekananFinishing = is_array($finisherId) && in_array('rekanan', $finisherId);
            @endphp
            <input class="form-check-input" type="checkbox" id="finishing{{ $finishing->employee->id }}" name="finisher[]" value="{{ $finishing->employee->id }}" {{ $isCheckedFinishing ? 'checked' : '' }}>
            <label for="finishing{{ $finishing->employee->id }}" class="form-check-label">{{ $finishing->employee->name }}</label>
        </div>
      </div>
      @endforeach
      <div class="col-sm">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="finishingRekanan" name="finisher[]" value="rekanan" {{ $rekananFinishing ? 'checked' : '' }}>
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
            @php
                $isCheckedQ = is_array($qualityId) && in_array($quality->id, $qualityId);
            @endphp
          <input class="form-check-input" type="checkbox" id="quality{{ $quality->id }}" name="quality[]" value="{{ $quality->id }}" {{ $isCheckedQ ? 'checked' : '' }}>
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
        {{-- Pilih Tempat Pengerjaan Menggunakan Checkbox --}}
        <h6>Tempat Pengerjaan :</h6>
        @php
            $isCheckedS = is_array($tempat) && in_array('Surabaya', $tempat);
            $isCheckedK = is_array($tempat) && in_array('Kediri', $tempat);
            $isCheckedM = is_array($tempat) && in_array('Malang', $tempat);
        @endphp
        <div class="col-sm">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="surabaya" name="tempat[]" value="Surabaya" {{ $isCheckedS ? 'checked' : '' }}>
                <label for="surabaya" class="form-check-label">Surabaya</label>
            </div>
        </div>
        <div class="col-sm">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="kediri" name="tempat[]" value="Kediri" {{ $isCheckedK ? 'checked' : '' }}>
                <label for="kediri" class="form-check-label">Kediri</label>
            </div>
        </div>
        <div class="col-sm">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="malang" name="tempat[]" value="Malang" {{ $isCheckedM ? 'checked' : '' }}>
                <label for="malang" class="form-check-label">Malang</label>
            </div>
        </div>
    </div>

    {{-- Memilih jenis mesin berdasarkan tempat --}}
    <div class="mb-3">
        <div class="form-group">
            <label>Jenis Mesin :</label>
            <select class="form-control select2" multiple="multiple" name="jenisMesin[]" style="width: 100%">

            </select>
            @if($tempat != null)
            <p class="text-sm text-danger font-italic mt-1">*Jika tidak ada perubahan, <strong>biarkan kosong.</strong></p>
            @endif

        </div>
    </div>

    <div class="mb-3">
        {{-- Masukkan start job --}}
        <label for="start_job" class="form-label">Mulai</label>
        <input type="datetime-local" class="form-control" id="start_job" aria-describedby="start_job" name="start_job" value="{{ $antrian->start_job }}">
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

@section('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Mesin",
            allowClear: true,
            ajax: {
                url: "{{ route('antrian.getMachine') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                        return {
                            text: item.machine_name,
                            id: item.machine_code
                        }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection
