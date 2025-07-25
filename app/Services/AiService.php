<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Gemini;

class AiService
{
    private static $client;

    public function init()
    {
        if (!self::$client) {
            $apiKey = getenv('GEMINI_KEY');
            $client = Gemini::client($apiKey);
        }
    }

    private static function makeText(Collection $todos)
    {
        $tasks = '';

        foreach ($todos as $tarefa) {
            $tasks .= "- $tarefa\n";
        }

        $text = "I have a list of tasks I need to complete. Please analyze them and create an optimized action plan for me to follow. Prioritize tasks based on urgency, importance, dependencies, and time required. Suggest how I can best organize my day (or week) to complete all tasks efficiently, including time blocks or schedules if necessary.
                Here's the list of tasks:
                $tasks
                Feel free to ask for clarification if needed before building the plan.
                
                NOTE: IT IS EXTREMELY IMPORTANT THAT YOU DO NOT USE MARKDOWN
                ";

        return $text;
    }

    public static function makeResume(Collection $todos)
    {
        self::init();

        $text = self::makeText($todos);

        $response = self::$client->generativeModel(
            model: 'gemini-2.0-flash'
        )->generateContent($text);

        return $response->text();
    }
}
