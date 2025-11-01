@extends('pages.users.layouts.app')

@section('title')
    Leaderboard Bulanan
@endsection

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Leaderboard Bulanan</h1>
        </div>

        <div class="section-body">
            <div class="card p-4">
                <form method="GET" action="{{ route('user.leaderboard.month') }}" class="row">
                    <div class="col-md-3">
                        <select name="month" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ (int)$month === $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" class="form-control">
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ (int)$year === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>

                <div class="table-responsive mt-3">
                    <table class="table table-striped">
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

