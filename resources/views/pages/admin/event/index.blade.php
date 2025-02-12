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
                        
                        <div class="card-header">
                            <a href="{{route('event.create')}}" class="btn btn-primary">
                                Tambah Acara
                            </a>
                        </div>
                        
                        <div class="card-body">
                            
                            <form method="GET" action="{{ route('event.index') }}">
                                <label for="priode">Filter Priode:</label>
                                <select name="priode" id="priode" onchange="this.form.submit()" class="form-control">
                                    @foreach ($availablePeriods as $period)
                                        <option value="{{ $period->priode }}" {{ $period->priode == $selectedPeriod ? 'selected' : '' }}>
                                            {{ $period->priode }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <br>
                            <div class="table-responsive">
                                <table class="table-striped table"
                                    id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Priode</th>
                                            <th>Deskripsi</th>
                                            <th>Poin</th>
                                            <th>Waktu</th>
                                            <th>More</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         
                                        
                                        @forelse ($events as $item)
                                        <tr>
                                             <td>{{$item->name}}</td>
                                             <td>{{$item->priode}}</td>
                                             <td>{{strip_tags($item->description)}}</td>
                                             <td>
                                               {{$item->point}}
                                             </td>
                                            <td>{{$item->date}}</td>
                                             <td>
                                                <a href="{{route('event.show', $item->id)}}" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                <a href="" class="btn btn-warning"><i class="fa fa-pen"></i></a>
                                                <a href="#" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('destroyForm').submit();"><i class="fa fa-trash"></i></a>
                                                <form id="destroyForm" action="{{route('event.destroy', $item->id)}}" method="post" style="display: none;">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit"></button>
                                                </form>
                                             </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{-- {{ $services->links('vendor.pagination.bootstrap-5') }} --}}
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