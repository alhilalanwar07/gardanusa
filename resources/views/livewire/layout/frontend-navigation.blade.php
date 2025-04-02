<?php

use App\Livewire\Actions\Logout;
use App\Models\Service;
use Livewire\Volt\Component;

new class extends Component {

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function with(): array
    {
        return [
            'services' => Service::orderBy('created_at', 'desc')->get(),
        ];
    }
}; ?>

<div>
    <footer id="footer" class="footer">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 footer-about">
                    <a href="/" class="logo d-flex align-items-center">
                        <span class="sitename">
                            CV. Garuda Digital Nusantara
                        </span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p class="gap-2"><i class="bi bi-geo-alt-fill"></i> Kec. Kolaka Kab. Kolaka Prov. Sulawesi Tenggara</p>
                        <p class="gap-2"><i class="bi bi-envelope-fill"></i> <span>garudadigitalnusantara@gmail.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 footer-links">
                    <h4>Tautan</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-3 footer-links">
                    <h4>
                        Layanan Kami
                    </h4>
                    <ul>
                        @foreach($services as $service)
                            <li><a href="/services/{{ $service->slug }}">{{ $service->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span>
                {{ date('Y') }}
                <strong class="px-1 sitename">
                    CV. Garuda Digital Nusantara
                </strong> <span>All Rights Reserved</span>
            </p>
        </div>

    </footer>
</div>