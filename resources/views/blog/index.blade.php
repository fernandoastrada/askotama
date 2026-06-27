@extends('layouts.app')

@push('styles')
<style>
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
<div class="container py-5">
    <div class="row g-4"> @forelse($posts as $post)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 blog-item shadow-sm">
                <div class="img-wrapper">
                    <span class="badge-category shadow-sm">{{ $post->category_blogs }}</span>
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                    @else
                        <img src="https://via.placeholder.com/600x400?text=No+Image" alt="No Image">
                    @endif
                </div>

                @auth
                <div class="card-footer bg-white border-top-0 d-flex gap-2 pb-4 px-4">
                    <a href="{{ route('blog.edit', $post->id) }}" class="btn btn-sm btn-warning rounded-pill px-3">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    
                    <form action="{{ route('blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
                @endauth

                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-2 text-muted small">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d M, Y') }}</span>
                    </div>

                    <h5 class="card-title mb-3">
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                            {{ $post->title }}
                        </a>
                    </h5>

                    <p class="card-text mb-4">
                        {{ $post->meta_description }}
                    </p>

                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://illustrations.popsy.co/gray/fogg-no-comments.png" alt="No Post" style="max-width: 250px;">
            <h4 class="mt-4 text-muted">Belum ada artikel yang diterbitkan.</h4>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center align-items-center gap-3 mt-5">

    @if($posts->onFirstPage())
        <span class="btn btn-light disabled">Previous</span>
    @else
        <a href="{{ $posts->previousPageUrl() }}" class="btn btn-outline-primary">
            Previous
        </a>
    @endif

    <span class="fw-bold">
        Page {{ $posts->currentPage() }}
        of
        {{ $posts->lastPage() }}
    </span>

    @if($posts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary">
            Next
        </a>
    @else
        <span class="btn btn-light disabled">Next</span>
    @endif

</div>
</div>
@endsection