@extends('pages.users.layouts.app')

@section('title', 'Detail Acara {{$event->name}}')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Acara</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Acara</a></div>
                <div class="breadcrumb-item">Detail Acara</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Detail Acara</h2>

            <div class="card">
                <form>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" value="{{ $event->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Priode</label>
                            <input type="text" class="form-control" value="{{ $event->priode }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" value="{{ $event->date }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Poin</label>
                            <input type="number" class="form-control" value="{{ $event->point }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Acara</label>
                            <p class="border p-3 rounded bg-light">{{ strip_tags($event->description) }}</p>
                        </div>
                    </div>
                    
                    <div class="card-footer text-right">
                        <a href="{{ route('event.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

            <!-- Form RSVP -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Konfirmasi Kehadiran (RSVP)</h4>
                </div>
                <form action="{{route('rsvp')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
            
                    <div class="card-body">
                        <div class="form-group">
                            <label>Unggah Bukti RSVP</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Kirim RSVP</button>
                    </div>
                </form>
            </div>
            
        </div>
</div>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.0/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
          selector: 'textarea',
          entity_encoding: 'raw',
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
          
        });
      </script>
    
@endpush