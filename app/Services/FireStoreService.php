<?php


namespace App\Services;


use Google\Cloud\Firestore\FirestoreClient;

class FireStoreService
{
    private static $instance = null;
    protected $firestore;

    // Private constructor to prevent multiple instances
    private function __construct()
    {
        $this->firestore = new FirestoreClient([
            'keyFilePath' => base_path(env('FIRESTORE_CREDENTIALS')),
            "projectId"=> "task-management-2346f",

        ]);
    }

    // Singleton access method
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new FirestoreService();
        }

        return self::$instance;
    }

    // Create a new Firestore document
    public function createTask($taskId, array $data)
    {
        return $this->firestore->collection('tasks')->document($taskId)->set($data);
    }

    // Update an existing Firestore document
    public function updateTask($taskId, array $data)
    {
        return $this->firestore->collection('tasks')->document($taskId)->update($data);
    }

    // Delete a Firestore document
    public function deleteTask($taskId)
    {
        return $this->firestore->collection('tasks')->document($taskId)->delete();
    }
}
