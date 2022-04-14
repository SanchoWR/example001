<?php

namespace App\Controller;


use App\Database\DbInterface;
use App\Entity\User;
use App\Http\JsonResponse;
use App\Http\RedirectResponse;
use App\Http\Response;
use App\Session\Session;
use App\SessionKey;
use App\Validator\Validator;
use App\Validator\ValidatorInterface;
use Twig\Environment;

class BaseController
{
    protected $twig;
    protected $session;
    protected $db;
    protected $validator;

    public function __construct(Environment $twig, Session $session, DbInterface $db, Validator $validator)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->db = $db;
        $this->validator = $validator;
    }

    protected function getUser(): ?User
    {
        $data = $this->session->get(SessionKey::USER);
        if (null === $data) {
            return null;
        }

        $user = unserialize($data, ['allowed_classes' => [User::class]]);

        if ($user instanceof User) {
            return $user;
        }

        throw new \Exception('Invalid User class');
    }

    protected function render(string $path, array $params = [], int $code = 200, array $headers = []): Response
    {
        $response = new Response();

        $body = $this->twig->render($path, $params);

        $response
            ->setHeaders($headers)
            ->setBody($body)
            ->setCode($code);

        return $response;
    }

    protected function redirectToUrl(string $url, int $code = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($url, $code, $headers);
    }

    protected function json($data, int $code = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse(json_encode($data), $code, ['Content-Type' => 'application/json']);
    }
}