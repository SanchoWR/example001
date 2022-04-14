<?php

namespace App\Core;


use App\Core\Exceptions\RouteNotFoundException;
use App\Database\DbInterface;
use App\Http\Request;
use App\Http\Response;
use App\Session\Session;
use App\Validator\ValidatorInterface;
use Twig\Environment;

class Router
{
    private $router;
    private $twig;
    private $session;
    private $db;
    private $validator;

    public function __construct(
        array              $router,
        Environment        $twig,
        Session            $session,
        DbInterface        $db,
        ValidatorInterface $validator
    )
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->session = $session;
        $this->db = $db;
        $this->validator = $validator;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function handle(Request $request): Response
    {
        $path = $request->getServer()->get('PATH_INFO', '/');
        $data = $this->getController($path);

        if (null === $data) {
            throw new RouteNotFoundException();
        }

        $controller = new $data['class']($this->twig, $this->session, $this->db, $this->validator);

        return call_user_func([$controller, $data['method']], $request);
    }

    private function getController(string $path): ?array
    {
        return $this->router[$path] ?? null;
    }
}
