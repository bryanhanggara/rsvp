@extends('pages.users.layouts.app')

@section('title')
    Leaderboard Periode
@endsection

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Leaderboard Periode</h1>
        </div>

        <div class="section-body">
            <div class="card p-4">
                <form method="GET" action="{{ route('user.leaderboard.periode') }}">
                    <div class="form-group">
                        <label for="periode">Pilih Periode:</label>
                        <select name="periode" id="periode" class="form-control d-inline" onchange="this.form.submit()">
                            @foreach($availablePeriods as $period)
                                <option value="{{ $period }}" {{ $selectedPeriod == $period ? 'selected' : '' }}>
                                    {{ $period }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Nama</th>
                                <th>Total Poin</th>
                                <th>Detail Event (poin - nama)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ranking as $index => $user)
                                <tr>
                                    <td>
                                        @if ($index == 0)
                                            🥇
                                        @elseif ($index == 1)
                                            🥈
                                        @elseif ($index == 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->total_points }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach ($user->rsvp as $r)
                                                <li>{{ $r->event->point }} - {{ $r->event->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

