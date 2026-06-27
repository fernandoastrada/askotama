@extends('layouts.app') {{-- Gunakan layout utama website Anda --}}

@section('title', 'Klien Terpercaya Kami | Askotama - Solusi Bisnis Terbaik')
@section('meta_description', 'Daftar klien dan partner bisnis terpercaya yang telah bekerja sama dengan Askotama. Kami mengutamakan kepuasan dan integritas dalam setiap kemitraan.')

@section('content')
<section class="bg-light py-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold text-dark mb-3">Klien & Mitra Kami</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            Dedikasi kami tercermin dari kepercayaan para mitra. Berikut adalah perusahaan dan institusi yang telah tumbuh bersama <strong>Askotama</strong>.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @forelse($clients as $client)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm client-card text-center p-4">
                        <div class="client-logo-wrapper mb-3 d-flex align-items-center justify-content-center">
                            {{-- Gunakan Icon Default atau Logo jika ada kolom image --}}
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-building text-primary fs-2"></i> 
                            </div>
                        </div>
                        <h2 class="h6 fw-bold mb-0 text-dark">{{ $client->name }}</h2>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada data klien untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

<section class="bg-dark text-white py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Siap Menjadi Mitra Kami Selanjutnya?</h2>
        <p class="mb-4">Bergabunglah dengan puluhan klien sukses lainnya bersama Askotama.</p>
        <a href="{{ route('contact.index') }}" class="btn btn-primary btn-lg px-5 shadow">Hubungi Kami Sekarang</a>
    </div>
</section>

<style>
    /* Custom Styling agar lebih menarik */
    .client-card {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    .client-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
        background-color: #f8f9fa;
    }
    .client-logo-wrapper {
        min-height: 100px;
    }
    h1 {
        font-size: 2.5rem;
        color: #2d3436;
    }
    .wgp-pagination .pagination {
        margin-bottom: 0;
    }
</style>
@endsection