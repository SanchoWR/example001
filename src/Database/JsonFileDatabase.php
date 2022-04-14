<?php

namespace App\Database;


class JsonFileDatabase implements DbInterface
{
    private $filePath;
    private $db;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        $data = file_get_contents($filePath);
        $records = json_decode($data, true);
        if (!is_array($records)) {
            $records = [];
        }

        $this->db = new DB($records);
    }

    private function saveToFile(): void
    {
        file_put_contents($this->filePath, json_encode($this->db->findAll()));
    }

    public function create(string $id, array $data): DbInterface
    {
        $this->db->create($id, $data);
        $this->saveToFile();

        return $this;
    }

    public function read(string $id): ?array
    {
        return $this->db->read($id);
    }

    public function update(string $id, array $data): DbInterface
    {
        $this->db->update($id, $data);
        $this->saveToFile();

        return $this;
    }

    public function delete(string $id): void
    {
        $this->db->delete($id);
        $this->saveToFile();
    }

    public function findOneBy(array $params): ?array
    {
        return $this->db->findOneBy($params);
    }

    public function findAll(): array
    {
        return $this->db->findAll();
    }
}