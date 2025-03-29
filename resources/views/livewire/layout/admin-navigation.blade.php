<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: false);
    }
}; ?>

<div>
    <div class="sidebar" data-background-color="white">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="#" class="logo text-white gap-2">
                    <img src="{{url('/')}}/favicon.ico" alt="Gardanusa" height="30" /> 
                    <span class="text-white">GARDANUSA</span>
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item {{ Route::is('home') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('home') }}" >
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Konten</h4>
                    </li>
                    <li class="nav-item {{ Route::is('admin.layanan*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.layanan') }}" wire:navigate>
                            <i class="fas fa-concierge-bell"></i>
                            <p>Layanan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.produk*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.produk') }}" wire:navigate>
                            <i class="fas fa-laptop"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.klien*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.klien') }}" wire:navigate>
                            <i class="fas fa-handshake"></i>
                            <p>Klien</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.tim*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.tim') }}" wire:navigate>
                            <i class="fas fa-user-friends"></i>
                            <p>Tim</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.blog*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.blog') }}" wire:navigate>
                            <i class="fas fa-newspaper"></i>
                            <p>Blog</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.portofolio*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.portofolio') }}" wire:navigate>
                            <i class="fas fa-briefcase"></i>
                            <p>Portofolio</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.testimoni*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.testimoni') }}" wire:navigate>
                            <i class="fas fa-quote-right"></i>
                            <p>Testimoni</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Tampilan</h4>
                    </li>
                    <li class="nav-item {{ Route::is('admin.slider*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-images"></i>
                            <p>Slider</p>
                        </a>
                    </li>                    
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Analitik</h4>
                    </li>
                    <li class="nav-item {{ Route::is('admin.pengunjung*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-chart-line"></i>
                            <p>Pengunjung</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.statistik*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-chart-pie"></i>
                            <p>Statistik</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Pengaturan</h4>
                    </li>
                    <li class="nav-item {{ Route::is('admin.umum*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-cog"></i>
                            <p>Umum</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.seo*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-search"></i>
                            <p>SEO</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('admin.backup*') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="fas fa-database"></i>
                            <p>Backup</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Pengguna</h4>
                    </li>
                    @if(auth()->user()->role == 'admin')
                    <li class="nav-item {{ Route::is('admin.manajemen-user') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.manajemen-user') }}" wire:navigate>
                            <i class="fas fa-users"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item {{ Route::is('profil') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('profil') }}" wire:navigate>
                            <i class="fas fa-user"></i>
                            <p>Profil</p>
                        </a>
                    </li>
                    <br>
                    <div class="px-4">
                        <li class="nav-item" style="padding: 0px !important;">
                            <a href="#" wire:click="logout" class=" text-center btn btn-sm btn-danger w-100 btn-block d-flex justify-content-center align-items-center" style="padding: 0px !important;">
                                <i class="fas fa-sign-out-alt fa-lg m-2 p-1"></i> &nbsp;
                                <p style="padding: 0px !important; margin: 5px !important">Keluar</p>
                            </a>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
