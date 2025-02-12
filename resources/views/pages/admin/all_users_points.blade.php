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
                    <h2>Total Poin Per Bulan</h2>

                        <!-- Form Filter -->
                        <div class="card shadow-sm p-3 bg-primary">
                            <!-- Form Filter -->
                            <form method="GET" action="{{ route('admin.pointsByMonth') }}" class="form-inline">
                                <label>Pilih Periode:</label>
                                <select name="periode" class="form-control mx-2">
                                    @foreach ($availablePeriods as $p)
                                        <option value="{{ $p }}" {{ $periode == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>

                                <label>Pilih Bulan:</label>
                                <select name="bulan" class="form-control mx-2">
                                    <option value="">Semua Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>

                                <button class="btn btn-success ml-2" type="submit">Filter</button>
                            </form>
                        </div>

                        <!-- Tabel Data -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Bulan</th>
                                    <th>Total Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if($bulan)
                                                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                                            @else
                                                Semua Bulan
                                            @endif
                                        </td>
                                        <td>{{ $user->total_point }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        
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