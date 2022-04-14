<?php

namespace App\Http;


class JsonResponse extends Response
{
    public function __construct($data = null, int $code = 200, array $headers = [])
    {
        parent::__construct($data, $code, $headers);
        $this->data = $data;
        $this->code = $code;
        $this->headers = $headers;
    }
}
