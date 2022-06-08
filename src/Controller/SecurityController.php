<?php

namespace App\Controller;


use App\Entity\User;
use App\Http\JsonResponse;
use App\Http\RedirectResponse;
use App\Http\Request;
use App\Http\Response;
use App\SessionKey;
use App\Util\PasswordUtil;

class SecurityController extends BaseController
{
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $login = $request->getRequest()->get('login');
            $pass = $request->getRequest()->get('pass');

            $data = $this->db->read($login);

            $error = 'Incorrect username or password';
            if (null === $data) {
                return $this->json(['errors' => $error]);
            }

            $user = User::createFromArray($data);
            if (!PasswordUtil::check($user, $pass)) {
                return $this->json(['errors' => $error]);
            }

            $this->session->set(SessionKey::USER, serialize($user));

            return $this->redirectToUrl('/');
        }

        $template = $request->isXmlHttpRequest() ? 'security/_login_form_layout.html.twig' : 'security/login.html.twig';

        return $this->render($template);
    }

    public function register(Request $request): Response
    {
        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {
            $login = $request->getRequest()->get('login');
            $pass = $request->getRequest()->get('pass');
            $confirmPass = $request->getRequest()->get('confirm_pass');
            $email = $request->getRequest()->get('email');
//            $name = $request->getRequest()->get('name');

            $user = new User($login, $pass, $email);
            $user->setConfirmPassword($confirmPass);

            $errors = $this->validator->validate($user);

            if (null !== $errors) {
                return $this->json(['errors' => $errors]);
            }

            $user->setPassword(PasswordUtil::hash($pass));
            $this->db->create($user->getUsername(), $user->toArray());

            $this->session->set(SessionKey::USER, serialize($user));

            return $this->redirectToUrl('/');
        }

        return $this->render('security/register.html.twig');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->session->destroy();

        return $this->redirectToUrl('/');
    }

    public function me(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw new \Exception('Method not allowed');
        }

        if (null === $user = $this->getUser()) {
            return $this->json(null);
        }

        $data = $user->toArray();
        unset($data[User::PASSWORD]);

        return $this->json($data);
    }
}
