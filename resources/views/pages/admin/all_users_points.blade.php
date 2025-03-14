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
            <div class="card">
               <div class="card-body">
                    <div class="container-fluid mt-5">
                        <h2>Jumlah Point User per Bulan</h2>

                        <form method="GET" action="{{ route('admin.pointsByMonth') }}">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select name="month" class="form-control">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="year" class="form-control">
                                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Total Point</th>
                                    <th>Minimum Point</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userPoints as $point)
                                    @php
                                        $isBelowMinimum = $point->total_points < ($eventPoints->minimum_point ?? 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $point->user->name }}</td>
                                        <td class="{{ $isBelowMinimum ? 'text-danger' : '' }}">
                                            {{ $point->total_points }}
                                        </td>
                                        <td>{{ $eventPoints->minimum_point ?? 0 }}</td>
                                        <td>
                                            @if ($isBelowMinimum)
                                                <span class="badge bg-danger text-white p-3">Kurang</span>
                                            @else
                                                <span class="badge bg-success text-white p-3">Cukup</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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