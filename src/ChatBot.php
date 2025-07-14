<?php
namespace AIChatBot;

use GuzzleHttp\Client;
use Dotenv\Dotenv;

class ChatBot
{
    private $client;
    private $apiKey;
    private $assistantId;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
        $this->assistantId = $_ENV['OPENAI_ASSISTANT_ID'] ?? '';
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'timeout'  => 60.0,
        ]);
    }

    public function ask($question, $session_id)
    {
        // Create thread for the session if not exists (simulate with session file)
        $thread_id = $this->getThreadId($session_id);

        // Add message to thread
        $message_id = $this->addMessageToThread($thread_id, $question);

        // Run assistant with file search enabled
        $run_id = $this->runAssistant($thread_id);

        // Poll run status until completed (max 15 seconds)
        $answer = $this->pollRunResult($thread_id, $run_id);

        return $answer;
    }

    // ===== Helpers below =====

    private function getThreadId($session_id)
    {
        $path = sys_get_temp_dir() . "/ai_chatbot_thread_{$session_id}.txt";
        if (file_exists($path)) {
            return trim(file_get_contents($path));
        }
        // Create new thread
        $resp = $this->client->post('threads');
        $json = json_decode($resp->getBody(), true);
        $thread_id = $json['id'] ?? '';
        file_put_contents($path, $thread_id);
        return $thread_id;
    }

    private function addMessageToThread($thread_id, $question)
    {
        $resp = $this->client->post("threads/{$thread_id}/messages", [
            'json' => [
                'role' => 'user',
                'content' => $question,
            ]
        ]);
        $json = json_decode($resp->getBody(), true);
        return $json['id'] ?? '';
    }

    private function runAssistant($thread_id)
    {
        $resp = $this->client->post("threads/{$thread_id}/runs", [
            'json' => [
                'assistant_id' => $this->assistantId,
            ]
        ]);
        $json = json_decode($resp->getBody(), true);
        return $json['id'] ?? '';
    }

    private function pollRunResult($thread_id, $run_id)
    {
        $tries = 0;
        do {
            usleep(1200000); // 1.2s
            $resp = $this->client->get("threads/{$thread_id}/runs/{$run_id}");
            $json = json_decode($resp->getBody(), true);
            $status = $json['status'] ?? '';
            $tries++;
        } while (!in_array($status, ['completed', 'failed', 'cancelled', 'expired']) && $tries < 15);

        if ($status !== 'completed') {
            return "Sorry, I couldn't get an answer from the Assistant.";
        }

        // Get messages
        $resp = $this->client->get("threads/{$thread_id}/messages");
        $json = json_decode($resp->getBody(), true);
        if (isset($json['data'])) {
            // Find latest 'assistant' message
            foreach (array_reverse($json['data']) as $msg) {
                if (($msg['role'] ?? '') === 'assistant') {
                    return $msg['content'][0]['text']['value'] ?? 'No response content.';
                }
            }
        }
        return 'No assistant response found.';
    }
}
