@extends('layouts.app')

@push('styles')
{{-- Masukkan CSS Plugin di sini --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .blog-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
    }
    .form-label {
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }
    .btn-publish {
        background-color: #4e73df;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-publish:hover {
        background-color: #2e59d9;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }
    /* Merapikan tampilan Summernote */
    .note-editor.note-frame {
        border: 1px solid #dee2e6 !important;
        border-radius: 10px !important;
    }
    .note-toolbar {
        background: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }
</style>
@endpush

@section('content')
<main class="pt-5 pb-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Edit Artikel</h2>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <p class="text-muted">Tulis konten berkualitas untuk meningkatkan SEO Anda.</p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn btn-light border btn-sm px-3">
                        <i class="fa fa-arrow-left me-1"></i> Batal
                    </a>
                </div>

                <div class="blog-card card">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label">Judul Artikel</label>
                        <input type="text" name="title" value="{{ $post->title }}" class="form-control" required>
                        </div>
                        
                        
                        <div class="mt-2">
                            <p class="small text-muted">Gambar saat ini:</p>
                            <img src="{{ asset($post->image) }}" width="150" class="rounded shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konten Utama</label>
                            <textarea id="summernote" name="content">{!! $post->content !!}</textarea>
                        </div>
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-publish btn-lg">Simpan Perubahan</button>
                        </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
{{-- Gunakan jQuery versi lengkap jika tersedia di layouts.app, jika tidak gunakan CDN ini --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Summernote
        $('#summernote').summernote({
            placeholder: 'Mulai menulis konten yang luar biasa di sini...',
            tabsize: 2,
            height: 450,
            lang: 'id-ID',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ],
            styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote']
        });

        // Fitur Hitung Karakter Meta Deskripsi
        $('#meta_desc').on('input', function() {
            let count = $(this).val().length;
            $('#charCount').text(count + ' / 160');
            if(count > 150) {
                $('#charCount').addClass('text-danger');
            } else {
                $('#charCount').removeClass('text-danger');
            }
        });
    });

    $('form').on('submit', function(e) {
    console.log("Form sedang mencoba dikirim...");
    
    // Cek apakah konten Summernote kosong
    if ($('#summernote').summernote('isEmpty')) {
        alert('Konten blog tidak boleh kosong!');
        e.preventDefault(); // Berhenti kirim jika kosong
    }
});
    
    function previewImage(input) {
    const preview = document.getElementById('img-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

</script>

@endpush