@extends('pages.users.layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <style>
        .card-square {
            width: 200px;
            height: 100%;
            text-align: center;
        }

        .card-icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 10px auto;
        }

    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard Beswan</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fa fa-shopping-basket fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Laundrian Hari ini</h4>
                            </div>
                            <div class="card-body">
                                10
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fa fa-dollar-sign fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pendapatan Hari ini</h4>
                            </div>
                            <div class="card-body">
                                42
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fa fa-spinner fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Sedang diproses</h4>
                            </div>
                            <div class="card-body">
                                1,201
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fa fa-file-invoice-dollar fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Belum dibayar</h4>
                            </div>
                            <div class="card-body">
                                47
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-header">
                <h1>Acara Terbaru</h1>
            </div>
            <div class="row justify-center">
                @forelse ($events as $item)
                <div class="col-md-3">
                    <a href="{{route('show.acara', $item->id)}}">
                        <div class="card card-statistic-1 card-square">
                            <div class="bg-primary d-flex justify-content-center align-items-center card-icon-circle mt-5">
                                <i class="fa fa-bolt fa-2x text-white"></i>
                            </div> 
                            <div class="card-wrap">
                                <div class="card-body">
                                   <h3> {{$item->name}}</h3>
                                   <p style="font-size: 16px;"> {{strip_tags($item->description)}}</p>
                                   <p style="font-size: 16px;"> {{$item->date}}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                    <div class="text-center p-5 justify-center d-flex">
                        <i class="fa-solid fa-calendar-xmark fa-4x text-danger"></i>
                        <h4 class="mt-3 text-muted">Belum ada acara</h4>
                    </div>                              
                @endforelse
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
