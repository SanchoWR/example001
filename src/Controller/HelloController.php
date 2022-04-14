<?php

namespace App\Controller;


use App\Http\Response;

class HelloController extends BaseController
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
