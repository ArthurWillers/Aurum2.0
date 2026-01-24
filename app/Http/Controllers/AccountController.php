<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Deletar a conta do usuário autenticado.
     */
    public function destroy(Request $request)
    {
        Auth::user()->delete();

        // Fazer logout
        Auth::logout();

        // Invalidar a sessão antiga
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Sua conta foi deletada com sucesso.');
    }
}
