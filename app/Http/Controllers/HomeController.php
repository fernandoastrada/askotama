<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Slide;
use Illuminate\Http\Request;
use App\Models\Client; // Import model Client

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where('status',1)->get()->take(3);
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('index',compact('slides','categories'));
    }
    public function clients()
{
    // Mengambil klien dengan paginasi (misal 12 per halaman)
    $clients = Client::orderBy('name', 'asc')->paginate(12);
    return view('clients.index', compact('clients'));
}
}
