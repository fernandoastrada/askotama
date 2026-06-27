@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->meta_description)

@push('styles')
<style>
    /* Menghilangkan kotak abu-abu dan merapikan layout */
    .custom-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        margin-bottom: 1rem;
    }

    .custom-breadcrumb .breadcrumb-item {
        display: flex;
        align-items: center;
        background: none !important; /* Menghilangkan kotak abu-abu */
        color: #6c757d;
    }

    /* Membuat pemisah (slash) manual jika CSS Bootstrap tidak terload */
    .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        padding: 0 10px;
        color: #dee2e6;
    }

    .custom-breadcrumb .breadcrumb-item a {
        color: #6c757d;
        transition: color 0.2s;
    }

    .custom-breadcrumb .breadcrumb-item a:hover {
        color: #0d6efd;
    }

    .custom-breadcrumb .breadcrumb-item.active {
        color: #0d6efd;
    }
    
    /* Mencegah link menampilkan URL di sebelahnya (kasus cetak/theme tertentu) */
    .breadcrumb-item a:after {
        content: "" !important;
    }
    .article-content {
        line-height: 1.8;
        font-size: 1.15rem;
        color: #2d3436;
    }
    .article-content h2, .article-content h3 {
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 20px 0;
    }
    .category-badge {
        background-color: #e3f2fd;
        color: #0d6efd;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .sticky-sidebar {
        position: sticky;
        top: 100px;
    }

    
    /* Card Container */
    .blog-item {
        transition: all 0.3s ease;
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .blog-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    /* Image Wrapper dengan Aspect Ratio 16:9 */
    .img-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden;
    }
    
    .img-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-item:hover .img-wrapper img {
        transform: scale(1.1);
    }

    /* Badge Category */
    .badge-category {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: #0d6efd;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        backdrop-filter: blur(5px);
        z-index: 10;
    }

    .card-title {
        font-weight: 700;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Batasi maksimal 2 baris judul */
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 3rem; /* Menjaga tinggi tetap konsisten */
    }

    .card-text {
        font-size: 0.9rem;
        color: #6c757d;
        display: -webkit-box;
        -webkit-line-clamp: 3; /* Batasi maksimal 3 baris deskripsi */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

</style>
@endpush

@section('content')
<article class="py-5" style="background-color: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb bg-transparent p-0 custom-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none text-muted flex-inline align-items-center">
                                <i class="fa fa-home me-1"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('blog.index') }}" class="text-decoration-none text-muted">Blog</a>
                        </li>
                        <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">
                            {{ Str::limit($post->title, 30) }}
                        </li>
                    </ol>
                </nav>

                <header class="mb-5">
                    <span class="category-badge mb-3 d-inline-block">{{ $post->category_blogs }}</span>
                    <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center text-muted">
                        <div class="me-3">
                            <i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d M Y') }}
                        </div>
                        <div>
                            <i class="far fa-user me-1"></i> Admin
                        </div>
                    </div>
                </header>

                @if($post->image)
                <div class="mb-5">
                    <img src="{{ asset($post->image) }}" class="img-fluid rounded-4 shadow-sm w-100" alt="{{ $post->title }}">
                </div>
                @endif

                <div class="article-content">
                    {!! $post->content !!}
                </div>

                <hr class="my-5">

                <div class="d-flex align-items-center justify-content-between bg-light p-4 rounded-4">
                    <h6 class="mb-0 fw-bold">Bagikan Artikel:</h6>
                    <div class="share-buttons">
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" class="btn btn-success btn-sm rounded-pill px-3" target="_blank">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" class="btn btn-primary btn-sm rounded-pill px-3" target="_blank">
                            <i class="fab fa-facebook me-1"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="sticky-sidebar">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Artikel Terbaru</h5>
                            @foreach($recentPosts as $recent)
                            <div class="d-flex mb-3">
                                <img src="{{ asset($recent->image) }}" class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1 fw-bold">
                                        <a href="{{ route('blog.show', $recent->slug) }}" class="text-decoration-none text-dark small">
                                            {{ Str::limit($recent->title, 45) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $recent->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card bg-primary text-white border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h4 class="fw-bold mb-3">Butuh Bantuan?</h4>
                            <p class="small mb-4">Konsultasikan kebutuhan Anda dengan tim ahli kami sekarang juga!</p>
                            <a href="https://wa.me/yournumber" class="btn btn-light fw-bold rounded-pill px-4">Hubungi Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection