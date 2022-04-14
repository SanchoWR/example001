<?php

namespace App;


use App\Core\Exceptions\RouteNotFoundException;
use App\Core\Router;
use App\Database\DbInterface;
use App\Database\JsonFileDatabase;
use App\Http\Request;
use App\Http\Response;
use App\Session\Session;
use App\Validator\Validator;
use App\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Kernel
{
    private $router;

    public function __construct()
    {
        $twigPaths = include dirname(__DIR__) . '/config/twig_paths.php';
        $databasefilePath = include dirname(__DIR__) . '/config/database.php';
        $loader = new FilesystemLoader($twigPaths);
        $twig = new Environment($loader);
        $db = $this->initDb($databasefilePath['path']);
        $validator = new Validator($db);
        $this->initRouter($twig, Session::create(), $db, $validator);
    }

    private function initDb(string $filePath): DbInterface
    {
        $this->createFile($filePath);

        return new JsonFileDatabase($filePath);
    }

    private function initRouter(Environment $twig, Session $session, DbInterface $db, ValidatorInterface $validator): void
    {
        $routes = include dirname(__DIR__) . '/config/routes.php';
        $this->router = new Router($routes, $twig, $session, $db, $validator);
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->router->handle($request);
        } catch (RouteNotFoundException $e) {
            return new Response('Page not found', 404);
        }
    }

    private function createFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            return;
        }

        $dir = dirname($filePath);

        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }

        touch($filePath);
    }
}
