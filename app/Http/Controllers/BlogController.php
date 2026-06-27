<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str; // Helper untuk membuat slug otomatis



class BlogController extends Controller
{
    // --- HALAMAN PUBLIK ---

    // 1. Menampilkan Daftar Blog
    public function index(Request $request)
{
    $query = Post::with('category')->latest();

    // Fitur Search
    if ($request->has('search')) {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('content', 'like', '%' . $request->search . '%');
    }

    // Fitur Filter Kategori
    if ($request->has('category')) {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('slug', $request->category);
        });
    }

    $posts = $query->paginate(9); // Gunakan pagination untuk SEO
    $categories = \App\Models\Category_blog::all();

    return view('blog.index', compact('posts', 'categories'));
}

    // 2. Menampilkan Detail Blog (SEO)
    public function show($slug)
    {
        // Cari artikel berdasarkan slug, jika tidak ada munculkan 404
        $post = \App\Models\Post::where('slug', $slug)->firstOrFail();
        
        // Opsional: Ambil 3 artikel terbaru untuk sidebar/rekomendasi
        $recentPosts = \App\Models\Post::where('id', '!=', $post->id)
                        ->latest()
                        ->take(3)
                        ->get();

        // Tambahkan visitor setiap kali artikel dibaca
        $post->increment('views');

        return view('blog.show', compact('post', 'recentPosts'));
    }

    // --- HALAMAN ADMIN ---

    // 3. Form Buat Artikel
    public function create()
    {
        $categories = \App\Models\Category_blog::all();
        return view('blog.create',compact('categories'));
    }

    // 4. Proses Simpan Artikel
    public function store(Request $request)
{
    // 1. Validasi (Pastikan namanya category_blogs)
    $request->validate([
        'title' => 'required',
        'category_blogs' => 'required', 
        'meta_description' => 'required',
        'content' => 'required',
    ]);

    $path = null; // Default null jika tidak ada gambar

    if ($request->hasFile('image')) {
        // 1. Ambil file
        $file = $request->file('image');
        
        // 2. Buat nama unik
        $imageName = time() . '.' . $file->extension();
        
        // 3. Pindahkan ke folder public/uploads/blog
        $file->move(public_path('uploads/blog'), $imageName);
        
        // 4. Set path untuk disimpan ke database
        $path = 'uploads/blog/' . $imageName;
    }

    // 2. Simpan
    Post::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'category_blogs' => $request->category_blogs, // <--- Baris ini yang sering terlewat
        'meta_description' => $request->meta_description,
        'content' => $request->content,
        'image' => $path ?? null,
    ]);

    return redirect()->route('blog.index')->with('success', 'Artikel berhasil diterbitkan!');
}

public function edit($id)
{
    $post = Post::findOrFail($id);
    return view('blog.edit', compact('post'));
}

public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);
    
    $request->validate([
        'title' => 'required',
        // 'category_blogs' => 'required',
        'content' => 'required',
        'image' => 'nullable|image|max:2048',
    ]);

    // Update Gambar jika ada yang baru
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/blog'), $imageName);
        $post->image = 'uploads/blog/' . $imageName;
    }

    $post->update([
        'title' => $request->title,
        'slug' => \Illuminate\Support\Str::slug($request->title),
        // 'category_blogs' => $request->category_blogs,
        // 'meta_description' => $request->meta_description,
        'content' => $request->content,
        'image' => $post->image,
    ]);

    return redirect()->route('blog.index')->with('success', 'Artikel berhasil diperbarui!');
}

public function destroy($id)
{
    $post = Post::findOrFail($id);
    
    // Hapus file gambar dari folder
    if ($post->image && file_exists(public_path($post->image))) {
        unlink(public_path($post->image));
    }
    
    $post->delete();
    return redirect()->route('blog.index')->with('success', 'Artikel berhasil dihapus!');
}

}