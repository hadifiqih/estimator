@extends('layouts.app')

@section('title', 'Dokumentasi')

@section('username', Auth::user()->name)

@section('page', 'Dokumentasi')

@section('breadcrumb', 'Upload Dokumentasi')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"></h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('antrian.storeDokumentasi') }}" method="POST" class="dropzone" id="my-dropzone" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $antrian->id }}">
                        <button type="submit" class="btn btn-sm btn-primary" id="submit-all">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
   //Script Dropzone
    Dropzone.options.myDropzone = {
     headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
     },
     url: "{{ route('antrian.storeDokumentasi') }}",
     autoProcessQueue: false,
     uploadMultiple: true,
     parallelUploads: 5,
     maxFiles: 5,
     maxFilesize: 30,
     acceptedFiles: 'image/*, .mp4, .mkv ',
     addRemoveLinks: true,
     dictRemoveFile: 'Hapus',
     dictFileTooBig: 'Ukuran file terlalu besar ({{ ini_get('upload_max_filesize') }}MB). Max ukuran file 30MB',
     dictInvalidFileType: 'Tipe file tidak didukung',
     dictMaxFilesExceeded: 'Maksimal file yang diupload 5 file',
     init: function() {
          var submitButton = document.querySelector("#submit-all");
          myDropzone = this;
          submitButton.addEventListener("click", function() {
                myDropzone.processQueue();
          });
          this.on("complete", function() {
                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                 var _this = this;
                 _this.removeAllFiles();
                }
                list_image();
          });
     },
     success: function(file, response) {
          list_image();
     },
     error: function(file, response) {
          return false;
     }
    };
</script>
@endsection
