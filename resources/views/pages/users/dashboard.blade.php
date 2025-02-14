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
                        <div class="card-icon bg-warning">
                            <i class="fas fa-coins fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Point Kamu Priode Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ auth()->user()->totalPointsForCurrentPeriod() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fa fa-calendar fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Kegiatan Yang diikuti Priode Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ auth()->user()->rsvpCountForCurrentPeriod() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fa fa-calendar fa-2x text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Kegiatan Yang diikuti Keseluruhan</h4>
                            </div>
                            <div class="card-body">
                                {{ $rsvp_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-header">
                <h1>Acara Terbaru</h1>
            </div>
            <form action="{{ route('dashboard.user') }}" method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <select name="month" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                    {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <select name="year" class="form-control">
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mt-3">
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                </div>
            </form>
            
            @if($events->isEmpty())
                <div class="d-flex justify-content-center align-items-center vh-50">
                    <div class="text-center">
                        <i class="fa-solid fa-calendar-xmark fa-4x text-danger"></i>
                        <h4 class="mt-3 text-muted">Belum ada acara</h4>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach ($events as $item)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 m-2">
                        <a href="{{route('show.acara', $item->id)}}">
                            <div class="card card-statistic-1 card-square">
                                <div class="bg-warning d-flex justify-content-center align-items-center card-icon-circle mt-5">
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
                    @endforeach
                </div>
            @endif

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
