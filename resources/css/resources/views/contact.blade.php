@extends('layouts.app')

@section('title', 'Hubungi Kami - PT. Askotama Inti Nusantara')
@section('description', 'Hubungi kami untuk informasi alat laboratorium berkualitas tinggi.')

@section('content')

<style>
    .main-nav {
    background-color: #24588F !important; /* transparan */
    position: absolute;
    top: 60px;
    left: 0;
    right: 0;
    z-index: 15; /* pastikan lebih tinggi dari banner content */
    /* background-color: #24588F; */
            padding: 0 20px;
}
    .Contact_header {
    background-image: url('/assets/images/Background_contact.png');
    background-size: cover;
    background-position: center;
    height: 520px; /* atur sesuai keinginan */
    position: relative;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    margin-top:15px;
}
.Contact_header::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3); /* overlay transparan gelap */
}

.Contact_header-content {
    position: relative;
    text-align: center;
    z-index: 2;
    margin-left: 50%;
}
.Contact_header h1 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 20px;
    color: #ffffff;
}
</style>
<div class="Contact_header">
    <div class="Contact_header-content">
        <h1>HUBUNGI KAMI</h1>
    <div>
        @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
      @endif
      <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input
            name="name"
            type="text"
            placeholder="Nama"
            class="p-3 w-full border rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
            autocomplete="name"
            aria-label="Nama"
        >
        <input
            name="email"
            type="email"
            placeholder="Email"
            class="p-3 w-full border rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
            autocomplete="email"
            aria-label="Email"
        >
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input
            name="company"
            type="text"
            placeholder="Perusahaan / Instansi"
            class="p-3 w-full border rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
            autocomplete="organization"
            aria-label="Perusahaan atau Instansi"
        >
        <input
            name="phone"
            type="text"
            placeholder="Nomor Handphone"
            class="p-3 w-full border rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
            autocomplete="tel"
            aria-label="Nomor Handphone"
        >
    </div>

    <textarea
        name="message"
        rows="5"
        placeholder="Permintaan Anda"
        class="p-3 w-full border rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
        aria-label="Permintaan Anda"
    ></textarea>

    <div class="text-right">
        <button
            type="submit"
            class="bg-blue-900 text-white py-2 px-6 rounded-full hover:bg-blue-800 transition duration-300"
        >
            SUBMIT
        </button>
    </div>
</form>


    </div>
    </div>
    </div>
</div>
{{-- <div style="background: url('/assets/images/Background_contact.png') no-repeat center center; background-size: cover; padding: 50px 0;">
  <div class="container mx-auto px-4 md:flex items-start gap-10">
    <div class="md:w-1/2">
      <h1 class="text-4xl font-bold text-blue-900 mb-4">HUBUNGI KAMI</h1>
      @if(session('success'))
        <p class="text-green-600 mb-4">{{ session('success') }}</p>
      @endif
      <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input name="name" type="text" placeholder="Nama" class="p-3 w-full border rounded" required>
          <input name="email" type="email" placeholder="Email" class="p-3 w-full border rounded" required>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input name="company" type="text" placeholder="Perusahaan / Instansi" class="p-3 w-full border rounded" required>
          <input name="phone" type="text" placeholder="Nomor Handphone" class="p-3 w-full border rounded" required>
        </div>
        <textarea name="message" rows="5" placeholder="Permintaan Anda" class="p-3 w-full border rounded" required></textarea>
        <button type="submit" class="bg-blue-900 text-white py-2 px-6 rounded-full hover:bg-blue-800">SUBMIT</button>
      </form>
    </div>

    <div class="md:w-1/2 mt-10 md:mt-0 text-blue-900">
      <p><b>Komplek Bumi Dirgantara Permai</b><br>
      Jl. Hercules Blok J1 No.10, Jatisari, Jatiasih, Bekasi 17426, Jawa Barat</p>
      <p class="mt-3">
        <a href="https://www.google.com/maps?q=Jl.+Hercules+Blok+J1+No.+10,+Jatisari,+Bekasi+17426" target="_blank" class="inline-flex items-center text-blue-800 hover:underline">
          <img src="/images/location-pin.svg" alt="Map Icon" class="w-5 mr-2"> Lihat Lokasi
        </a>
      </p>
      <p class="mt-3">info@askotama.co.id<br>0813 1155 4688 / 0852 8944 2030</p>
      <img src="/images/map-thumbnail.png" alt="Map" class="mt-4 rounded shadow-md">
    </div>
  </div>
</div> --}}
@endsection
