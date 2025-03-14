@extends('layouts.app')

@section('title','Berita')
@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        text: '{{ session('success') }}'
    });
</script>
@endif
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Acara</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Acara

                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                                            
                        <div class="card-body">
                            <div class="container-fluid">
                                <h2>Akumulasi Point</h2>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Total Point</th>
                                            <th>Standart Point</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pointsPerMonth as $point)
                                            <tr>
                                                <td>{{ $month[$point->bulan] }}</td>
                                                <td>{{ $point->tahun }}</td>
                                                <td>{{ $point->total_point }}</td>
                                                <td>{{ $point->minimum_point }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
@endpush