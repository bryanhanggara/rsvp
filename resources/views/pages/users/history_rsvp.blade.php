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
            <h1>Riwayat Acara Yang diikuti</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Acara</a></div>
                <div class="breadcrumb-item">Riwayat Acara Yang diikuti</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Riwayat Acara Yang diikuti</h2>

            <div class="card">              
                    <div class="card-body">
                        @foreach ($rsvps as $periode => $events)
                            <h3>Periode: {{ $periode }}</h3>
                            <table class="table table-striped">
                                <tr>
                                    <th>No</th>
                                    <th>Event</th>
                                    <th>Tanggal RSVP</th>
                                    <th>Status</th>
                                </tr>
                                @foreach ($events as $rsvp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $rsvp->event->name }}</td>
                                        <td>{{ $rsvp->created_at->format('d M Y') }}</td>
                                        <td>{{ $rsvp->status }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endforeach
                    </div>
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