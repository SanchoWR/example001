<?php

namespace App\Http;


class ArrayObject
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function delete(string $key): self
    {
        unset($this->data[$key]);

        return $this;
    }
}
