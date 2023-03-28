<ul class="nav">
    <li class="nav-item {{ $title == 'Dashboard' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard.index') }}">
            <i class="mdi mdi-view-dashboard menu-icon"></i>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

    @if(\Auth::user()->role_id == 1)
    <li class="nav-item {{ $title == 'Data Pasang Baru' || $title == 'Detail Pasang Baru' || $title == 'Edit Pasang Baru' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('data-pasang-baru.index') }}">
            <i class="mdi mdi-briefcase-check menu-icon"></i>
            <span class="menu-title">Data Pasang Baru</span>
        </a>
    </li>

    <li class="nav-item {{ $title == 'Data Job' || $title == 'Detail Job' || $title == 'Edit Data Job' || $title == 'Detail Data Job' || $title == 'Teknisi Cadangan' || $title == 'Teknisi Non Job' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('data-job.index') }}">
            <i class="mdi mdi-buffer menu-icon"></i>
            <span class="menu-title">Data Job</span>
        </a>
    </li>

    <li class="nav-item {{ $title == 'Absensi Karyawan' || $title == 'Edit Absensi Karyawan' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absensi.index') }}">
            <i class="mdi mdi-calendar-clock menu-icon"></i>
            <span class="menu-title">Absensi</span>
        </a>
    </li>

    <li class="nav-item {{ $title == 'Data Karyawan' || $title == 'Detail Karyawan' || $title == 'Update Password Karyawan' || $title == 'Data Karyawan Terhapus' ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="#karyawan" aria-expanded="false" aria-controls="karyawan">
            <i class="mdi mdi-account-multiple menu-icon"></i>
            <span class="menu-title">Data Karyawan</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ $title == 'Data Karyawan' || $title == 'Detail Karyawan' || $title == 'Update Password Karyawan' || $title == 'Data Karyawan Terhapus' ? 'show' : '' }}" id="karyawan" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('karyawan.index') }}"> List Karyawan</a></li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ $title == 'Pengaturan Aplikasi' || $title == 'Whatsapp API' ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="#setting" aria-expanded="false" aria-controls="setting">
            <i class="mdi mdi-settings menu-icon"></i>
            <span class="menu-title">Setting</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ $title == 'Pengaturan Aplikasi' || $title == 'Whatsapp API' ? 'show' : '' }}" id="setting" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('setting.index') }}"> Pengaturan Aplikasi</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('whatsapp-api.index') }}"> Whatsapp API</a></li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ $title == 'API Key' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('api-key.index') }}">
            <i class="mdi mdi-lock menu-icon"></i>
            <span class="menu-title">API Key</span>
        </a>
    </li>

    <li class="nav-item {{ $title == 'Data Role' || $title == 'Detail Role' || $title == 'Edit Role' ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="#role_permission" aria-expanded="false" aria-controls="role_permission">
            <i class="mdi mdi-account-key menu-icon"></i>
            <span class="menu-title">Hak Akses</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ $title == 'Data Role' || $title == 'Detail Role' || $title == 'Edit Role' ? 'show' : '' }}" id="role_permission" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('role.index') }}"> Role</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('permission.index') }}"> Permission</a></li>
            </ul>
        </div>
    </li>
    @endif

    <li class="nav-item {{ $title == 'Profile Setting' || $title == 'Update Password' ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Akun Saya</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ $title == 'Profile Setting' || $title == 'Update Password' ? 'show' : '' }}" id="auth" style="">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.index') }}"> Update Profile </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile.update_password') }}"> Update Password </a></li>
            </ul>
        </div>
    </li>
</ul>
