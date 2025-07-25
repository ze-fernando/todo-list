<?php

namespace App\Http\Controllers\Api\Todo;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TodoController extends Controller
{
    public function index()
    {
        try {
            $id = Auth::id();

            $todos = Todo::where('user_id', $id)->get();

            return response()->json($todos, 200);
        } catch (\Throwable $th) {
            Log::info('Erro ao listar todos ' . $th->getMessage());

            return response()->json(['Erro ao listar todos ' => $th->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $todo = Todo::find($id);

            if (!$todo) {
                return response()->json(['Erro ao buscar todos ' => 'Todo não encotrada'], 404);
            }

            return response()->json($todo, 200);
        } catch (\Throwable $th) {
            Log::info('Erro ao buscar todo ' . $th->getMessage());

            return response()->json(['Erro ao buscar todo ' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|unique:todos,title',
                'description' => 'sometimes|string',
                'expire' => 'required|date',
                'done' => 'sometimes|boolean',
                'priority' => Rule::in('low', 'medium', 'high')
            ]);

            $todo = Todo::create($data);

            return response()->json($todo, 201);
        } catch (\Throwable $th) {
            Log::info('Erro ao criar todo ' . $th->getMessage());

            return response()->json(['Erro ao criar todo' => $th->getMessage()], 500);
        }
    }

    public function update(int $id)
    {
        try {
            $todo = Todo::find($id);

            if (!$todo) {
                return response()->json(['Erro' => 'Todo não encontrada'], 404);
            }

            $todo->setDone();
            $todo->save();

            return response()->json($todo, 200);
        } catch (\Throwable $th) {
            Log::info('Erro ao atualizar todo ' . $th->getMessage());

            return response()->json(['Erro ao atualizar todo ' => $th->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $todo = Todo::find($id);

            if (!$todo) {
                return response()->json(['Erro' => 'Todo não encontrada'], 404);
            }

            $todo->delete();

            return response()->json();
        } catch (\Throwable $th) {
            Log::info('Erro ao atualizar todo ' . $th->getMessage());

            return response()->json(['Erro ao atualizar todo ' => $th->getMessage()], 500);
        }
    }
}
