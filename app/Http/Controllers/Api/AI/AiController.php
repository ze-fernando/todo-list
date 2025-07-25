<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Services\AiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function getResume()
    {
        try {
            $userId = Auth::id();

            $todos = Todo::where('user_id', $userId)->get();

            $resume = AiService::makeResume($todos);

            return response()->json(['resume' => $resume]);
        } catch (\Throwable $th) {
            Log::error('Erro ao gerar resumo' . $th->getMessage());

            return response()->json(['Error' => $th->getMessage()]);
        }
    }
}
