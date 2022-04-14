<?php

namespace App\Database;


interface DbInterface
{
    public function create(string $id, array $data): self;

    public function read(string $id): ?array;

    public function update(string $id, array $data): self;

    public function delete(string $id): void;

    public function findOneBy(array $params): ?array;

    public function findAll(): array;
}
