<?php

namespace App\Database;


class DB implements DbInterface
{
    private $records;

    public function __construct(array $records)
    {
        $this->records = $records;
    }

    public function create(string $id, array $data): DbInterface
    {
        $this->records[$id] = $data;

        return $this;
    }

    public function read(string $id): ?array
    {
        return $this->records[$id] ?? null;
    }

    public function update(string $id, array $data): DbInterface
    {
        $this->records[$id] = $data;

        return $this;
    }

    public function delete(string $id): void
    {
        unset($this->records[$id]);
    }

    public function findOneBy(array $params): ?array
    {
        $records = array_filter($this->records, function (array $record) use ($params) {
            $exists = true;
            foreach ($params as $fieldName => $value) {
                $exists = $exists && $record[$fieldName] === $value;
            }

            return $exists;
        });

        $countRecords = count($records);
        if ($countRecords > 1) {
            throw new \Exception('Record most be one');
        }

        return $countRecords ? $records : null;
    }

    public function findAll(): array
    {
        return $this->records;
    }
}
