<?php

namespace App\Http\Controllers;

use App\Models\Post; // Ganti dengan nama Model blog Anda
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        // Ambil semua artikel yang statusnya dipublikasikan
        // Sesuaikan query dengan nama tabel/kolom di database Anda
        $posts = Post::latest()->get();

        return response()->view('sitemap', [
            'posts' => $posts,
        ])->header('Content-Type', 'text/xml');
    }
}