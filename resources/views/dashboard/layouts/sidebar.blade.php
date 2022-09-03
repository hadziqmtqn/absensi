<ul class="nav">
    <li class="nav-item {{ ($title == 'Dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="mdi mdi-view-dashboard menu-icon"></i>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

    @if(\Auth::user()->role_id == 1)
    <li class="nav-item {{ ($title == 'Data Pasang Baru' OR $title == 'Detail Pasang Baru' OR $title == 'Edit Pasang Baru') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('data-pasang-baru.index') }}">
            <i class="mdi mdi-briefcase-check menu-icon"></i>
            <span class="menu-title">Data Pasang Baru</span>
        </a>
    </li>

    <li class="nav-item {{ ($title == 'Data Job' OR $title == 'Detail Job' OR $title == 'Edit Data Job' OR $title == 'Detail Data Job') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('data-job.index') }}">
            <i class="mdi mdi-buffer menu-icon"></i>
            <span class="menu-title">Data Job</span>
        </a>
    </li>
    @endif

    <li class="nav-item {{ ($title == 'Absensi Karyawan') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absensi.index') }}">
            <i class="mdi mdi-calendar-clock menu-icon"></i>
            <span class="menu-title">Absensi</span>
        </a>
    </li>

    @if (\Auth::user()->role_id == 1)
    <li class="nav-item {{ ($title == 'Data Karyawan' OR $title == 'Detail Karyawan' OR $title == 'Update Password Karyawan') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('karyawan.index') }}">
            <i class="mdi mdi-account-multiple menu-icon"></i>
            <span class="menu-title">Data Karyawan</span>
        </a>
    </li>
    <li class="nav-item {{ ($title == 'Pengaturan Aplikasi' ? 'active' : '') }}">
        <a class="nav-link" href="{{ route('setting.index') }}">
            <i class="mdi mdi-settings menu-icon"></i>
            <span class="menu-title">Pengaturan Aplikasi</span>
        </a>
    </li>
    @endif

    <li class="nav-item {{ ($title == 'Profile Setting' OR $title == 'Update Password') ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Akun Saya</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ ($title == 'Profile Setting' OR $title == 'Update Password') ? 'show' : '' }}" id="auth" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.index') }}"> Update Profile </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.update_password') }}"> Update Password </a></li>
            </ul>
        </div>
    </li>
</ul>