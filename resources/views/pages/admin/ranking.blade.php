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
            <h1>Ranking Beswan Terbanyak</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Ranking Beswan Terbanyak

                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card p-5">        
                <div class="card-body">
                  <!-- Filter Periode -->
                <form method="GET" action="{{ route('ranking.index') }}">
                        <label for="periode">Pilih Periode:</label>
                        <select name="periode" id="periode" class="form-control d-inline" onchange="this.form.submit()">
                            @foreach($availablePeriods as $period)
                                <option value="{{ $period }}" {{ $selectedPeriod == $period ? 'selected' : '' }}>
                                    {{ $period }}
                                </option>
                            @endforeach
                        </select>
                        
                </form>
                    <!-- Tabel Ranking -->
                <table class="table table-striped mt-4">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Beswan</th>
                            <th>Point diperoleh</th>
                            <th>Total Kegiatan yang hadir</th>
                            <th>Kegiatan yang hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ranking as $index => $user)
                            <tr>
                                <td>
                                    
                                        @if ($index == 0)
                                            🥇
                                        @elseif ($index == 1)
                                            🥈
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->total_points ?? 0 }}</td>
                                <td>{{ $user->total_rsvp ?? 0 }}</td>
                                <td>
                                    <ul>
                                        @foreach ($user->rsvp as $point)
                                            <li>{{ $point->event->point }} poin - {{ $point->event->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
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