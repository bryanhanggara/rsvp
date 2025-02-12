<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">HangsPos</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
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
                <a href="{{route('event.index')}}"
                    class="nav-link"><i class="fas fa-user"></i><span>Acara</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route('ranking.index')}}"
                    class="nav-link"><i class="fa fa-trophy"></i><span>Ranking</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.pointsByMonth')}}"
                    class="nav-link"><i class="fa fa-coins"></i><span>Point</span></a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{route('package.index')}}"
                    class="nav-link"><i class="fa fa-tshirt"></i><span>Paket Laundrian</span></a>
            </li> --}}
        </ul>
        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs"
                class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div>
    </aside>
</div>
