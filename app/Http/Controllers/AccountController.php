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
        $user = Auth::user();

        // Fazer logout
        Auth::logout();

        // Invalidar a sessão antiga
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Deletar o usuário
        $user->delete();

        // Iniciar uma nova sessão para a mensagem
        $request->session()->regenerate();
        $request->session()->flash('status', 'Sua conta foi deletada com sucesso.');

        return redirect('/');
    }
}
