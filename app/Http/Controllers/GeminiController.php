<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Cliente HTTP do Laravel

class GeminiController extends Controller
{
    public function gerarIdeia(Request $request)
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'Chave de API do Gemini não configurada no servidor.'], 500);
        }

        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";

        $prompt = "Gere uma ideia de produto eletrônico criativo e vendável para uma loja online chamada 'Majestic Store'. Forneça um nome para o produto e uma descrição curta e atraente em português do Brasil. Formate a resposta como um objeto JSON com as chaves 'nome' e 'descricao'.";

        try {
            $response = Http::post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => "application/json"
                ]
            ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'Falha na comunicação com a API do Gemini.'], $response->status());
            }
            return $response->json();

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor ao processar o pedido.'], 500);
        }
    }
}