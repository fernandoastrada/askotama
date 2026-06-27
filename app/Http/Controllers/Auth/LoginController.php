<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // penting! gunakan ini

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Default redirect path.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Buat konstruktor
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override redirect setelah login sukses
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->utype === 'ADM') {
            return redirect()->route('admin.index');
        }

        if ($user->utype === 'USR') {
            return redirect()->route('home.index'); // pastikan route('home') ada
        }

        // fallback
        return redirect($this->redirectTo);
    }
}
