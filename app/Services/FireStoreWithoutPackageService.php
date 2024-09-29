<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class FireStoreWithoutPackageService
{
     const FIRESTORE_SCOPE = 'https://www.googleapis.com/auth/datastore';
     const FIRESTORE_TOKEN_URL = 'https://oauth2.googleapis.com/token';
     const FIRESTORE_API_BASE_URL = 'https://firestore.googleapis.com/v1/projects';

    /**
     * Get Firestore access token using Google Service Account credentials.
     *
     * @return string
     */
    private function getFirestoreAccessToken(): string
    {
        $credentialsPath = base_path(env('FIRESTORE_CREDENTIALS'));
        $credentials = json_decode(file_get_contents($credentialsPath), true);

        $privateKey = $credentials['private_key'];
        $clientEmail = $credentials['client_email'];
        $now = time();

        // Create JWT token
        $payload = [
            "iss" => $clientEmail,
            "scope" => self::FIRESTORE_SCOPE,
            "aud" => self::FIRESTORE_TOKEN_URL,
            "iat" => $now,
            "exp" => $now + 3600, // 1 hour expiration
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        // Request the access token from Google OAuth
        $response = Http::asForm()->post(self::FIRESTORE_TOKEN_URL, [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed()) {
            Log::error('Error retrieving Firestore access token', ['response' => $response->body()]);
            throw new \Exception('Failed to retrieve Firestore access token');
        }

        return $response->json('access_token');
    }

    /**
     * Build the Firestore document URL for a given task.
     *
     * @param string $taskId
     * @return string
     */
    private function getTaskUrl(string $taskId): string
    {
        $projectId = env('FIRESTORE_PROJECT_ID');
        return sprintf("%s/%s/databases/(default)/documents/tasks/%s", self::FIRESTORE_API_BASE_URL, $projectId, $taskId);
    }

    /**
     * Prepare Firestore-compatible field data.
     *
     * @param array $data
     * @return array
     */
    private function prepareFirestoreFields(array $data): array
    {
        return array_map(function ($value) {
            if (is_string($value)) {
                return ['stringValue' => $value];
            } elseif (is_int($value)) {
                return ['integerValue' => $value];
            } elseif (is_bool($value)) {
                return ['booleanValue' => $value];
            }
            // Add handling for other types like arrays, null, floats if needed.
            return ['nullValue' => null]; // Default for unsupported types
        }, $data);
    }

    /**
     * Create or update a task in Firestore.
     *
     * @param string $taskId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createTask(string $taskId, array $data): array
    {
        if (empty($taskId) || empty($data)) {
            throw new \InvalidArgumentException('Task ID and data cannot be empty.');
        }

        $accessToken = $this->getFirestoreAccessToken();
        $url = $this->getTaskUrl($taskId);

        $response = Http::withToken($accessToken)->patch($url, [
            'fields' => $this->prepareFirestoreFields($data),
        ]);

        if ($response->failed()) {
            Log::error('Error creating/updating Firestore task', ['response' => $response->body()]);
            throw new \Exception('Failed to create/update task in Firestore');
        }

        return $response->json();
    }

    public function updateTask(string $taskId, array $data): array{
        return $this->createTask($taskId,$data);
    }
    /**
     * Delete a task from Firestore.
     *
     * @param string $taskId
     * @return array
     * @throws \Exception
     */
    public function deleteTask(string $taskId): array
    {
        if (empty($taskId)) {
            throw new \InvalidArgumentException('Task ID cannot be empty.');
        }

        $accessToken = $this->getFirestoreAccessToken();
        $url = $this->getTaskUrl($taskId);

        $response = Http::withToken($accessToken)->delete($url);

        if ($response->failed()) {
            Log::error('Error deleting Firestore task', ['response' => $response->body()]);
            throw new \Exception('Failed to delete task from Firestore');
        }

        return $response->json();
    }
}
