<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EstoqueController extends Controller
{
    public function testarApi()
    {
        $response = Http::withToken(config('anthropic.auth_token'))
            ->post(config('anthropic.base_url') . '/v1/chat/completions', [
                'model' => config('anthropic.default_models.haiku'),
                'messages' => [
                    ['role' => 'user', 'content' => 'Olá, tudo bem?']
                ],
            ]);

        // Mostra a resposta da API
        dd($response->json());
    }
}
