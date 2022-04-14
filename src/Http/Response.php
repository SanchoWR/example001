<?php

namespace App\Http;


class Response
{
    private $body;
    private $headers;
    private $code;

    public function __construct(?string $body = null, int $code = 200, array $headers = [])
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->code = $code;
    }

    public function send()
    {
        $this->sendHeaders();
        http_response_code($this->code);
        echo $this->body;
    }

    public function sendHeaders(): void
    {
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }
}
