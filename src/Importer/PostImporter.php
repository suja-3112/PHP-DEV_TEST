<?php

namespace silverorange\DevTest\Importer;

use PDO;
use Exception;

class PostImporter
{
    private PDO $db;
    private string $dataDirectory;

    public function __construct(PDO $db, string $dataDirectory)
    {
        $this->db = $db;
        $this->dataDirectory = rtrim($dataDirectory, '/');
    }

    public function import(): void
    {
        $files = glob($this->dataDirectory . '/*.json');

        foreach ($files as $file) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Skipping invalid JSON file: $file\n";
                continue;
            }

            try {
                $this->importPost($data);
                echo "Imported post: " . $data['title'] . "\n";
            } catch (Exception $e) {
                echo "Failed to import post from file $file: " . $e->getMessage() . "\n";
            }
        }
    }

    private function importPost(array $data): void
    {
        $sql = <<<SQL
INSERT INTO posts (id, title, body, created_at, modified_at, author)
VALUES (:id, :title, :body, :created_at, :modified_at, :author)
ON CONFLICT (id) DO NOTHING;
SQL;

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':body' => $data['body'],
            ':created_at' => $data['created_at'],
            ':modified_at' => $data['modified_at'],
            ':author' => $data['author'],
        ]);
    }
}
