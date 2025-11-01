<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand p-5 mb-5">
            <img src="{{url('img/avatar/logo_kse.jpeg')}}" alt="LOGO" width="100">
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">KSE</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item">
                <a href="{{route('dashboard.admin')}}"
                    class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li class="menu-header">Layanan</li>
            <li class="nav-item">
                <a href="{{route('show.history')}}"
                    class="nav-link"><i class="fas fa-clock"></i><span>Riwayat RSPV</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.leaderboard.periode') }}"
                    class="nav-link"><i class="fas fa-trophy"></i><span>Leaderboard Periode</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.leaderboard.month') }}"
                    class="nav-link"><i class="fas fa-calendar-alt"></i><span>Leaderboard Bulanan</span></a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{route('package.index')}}"
                    class="nav-link"><i class="fa fa-shopping-cart"></i><span>Riwayat</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route('package.index')}}"
                    class="nav-link"><i class="fa fa-tshirt"></i><span>Paket Laundrian</span></a>
            </li> --}}
        </ul>
        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="#"
                class="btn btn-warning btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Donate
            </a>
        </div>
    </aside>
</div>
