<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="mdi mdi-view-dashboard menu-icon"></i>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

    @if (\auth::user()->role_id == 1)
    <li class="nav-item {{ $title == 'Detail Karyawan' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('karyawan.index') }}">
            <i class="mdi mdi-account-multiple menu-icon"></i>
            <span class="menu-title">Data Karyawan</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('setting.index') }}">
            <i class="mdi mdi-settings menu-icon"></i>
            <span class="menu-title">Pengaturan Aplikasi</span>
        </a>
    </li>
    @endif

    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Akun Saya</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="auth" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.index') }}"> Update Profile </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.update_password') }}"> Update Password </a></li>
            </ul>
        </div>
    </li>
</ul>