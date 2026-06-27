<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        // Kirim email / simpan ke database (opsional)
        // Mail::to('admin@yourdomain.com')->send(new ContactFormMail($request->all()));

        return redirect()->back()->with('success', 'Pesan Anda berhasil dikirim!');
    }
}
