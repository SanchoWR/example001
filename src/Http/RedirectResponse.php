<?php

namespace App\Http;


class RedirectResponse extends Response
{
    private $url;

    public function __construct(string $url, int $code = 302, array $headers = [])
    {
        parent::__construct('', $code, $headers);
        $this->setTargetUrl($url);
    }

    public function setTargetUrl($url)
    {
        if ('' === ($url ?? '')) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->url = $url;

        $this->setBody(
            sprintf(
                '<!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="UTF-8" />
                        <meta http-equiv="refresh" content="0;url=\'%1$s\'" />
                
                        <title>Redirecting to %1$s</title>
                    </head>
                    <body>
                        Redirecting to <a href="%1$s">%1$s</a>.
                    </body>
                </html>',
                htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8')
            )
        );

        $this->setHeaders(['Location' => $this->url]);

        return $this;
    }
}
