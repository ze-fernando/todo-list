<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use AiService;

class AiController extends Controller
{
    public function index(Request $request)
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
