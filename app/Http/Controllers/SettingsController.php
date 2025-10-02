<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $passwordConfirmedAt = $request->session()->get('auth.password_confirmed_at');
        $timeElapsed = $passwordConfirmedAt ? now()->unix() - $passwordConfirmedAt : null;

        // Verificar se passou menos de 10 minutos (600 segundos)
        $isRecentlyConfirmed = $passwordConfirmedAt && $timeElapsed < 600;

        // Calcular tempo restante em segundos
        $timeRemaining = $isRecentlyConfirmed ? 600 - $timeElapsed : 0;

        return view('settings', [
            'isRecentlyConfirmed' => $isRecentlyConfirmed,
            'timeRemaining' => $timeRemaining,
        ]);
    }
}
