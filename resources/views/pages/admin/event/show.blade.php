@extends('layouts.app')

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
            <div class="card mt-4 p-3">
                <form action="{{ route('rsvp.bulkUpdateStatus') }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <div class="card-header">
                        <h4>Daftar Kehadiran (RSVP)</h4>
                    </div>
                    <div class="container-fluid">
                        <table class="table table-striped">
                            <thead>
                                <th>
                                    <input type="checkbox" id="selectAll"> Pilih Semua
                                </th>
                                <th>Nomor</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Bukti RSVP</th>
                                <th>Ubah Status</th>
                            </thead>
                            <tbody>
                                @forelse ($users as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="rsvp_ids[]" value="{{ $item->id }}" class="rsvp-checkbox">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $item->status == 'PENDING' ? 'badge-warning' : 
                                                   ($item->status == 'APPROVED' ? 'badge-success' : 'badge-danger') }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ asset('storage/' . $item->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $item->image) }}" width="300" >
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('rsvp.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control">
                                                    <option value="PENDING" {{ $item->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                                    <option value="APPROVED" {{ $item->status == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                                                    <option value="REJECTED" {{ $item->status == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada RSVP</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                
                        <div class="mt-3">
                            <select name="status" class="form-control" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="PENDING">PENDING</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="REJECTED">REJECTED</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Update Status Terpilih</button>
                        </div>
                    </div>
                </form>
                
                <script>
                    document.getElementById('selectAll').addEventListener('change', function() {
                        let checkboxes = document.querySelectorAll('.rsvp-checkbox');
                        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                    });
                </script>
                
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