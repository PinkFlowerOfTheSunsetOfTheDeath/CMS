<?php

namespace App\Controllers;


use App\Repositories\UserRepository;

class AuthController extends BaseController
{
    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function registerAction()
    {
        return $this->render('auth/newAccount.html.twig');
    }

    public function saveAction()
    {
        // add new user
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function loginAction()
    {
        return $this->render('auth/login.html.twig');
    }

    public function getAccount()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $userName = $_POST['username'];
            $password = $_POST['password'];

            $userRepository = new UserRepository();
            $user = $userRepository->getByUsername($userName);

            if (empty($user)) {
                header("Location: /login");
            }

            $validPassword = $user->checkPassword($password);

            if (!$validPassword) {
                header("Location: /login");
            }

            $_SESSION['user'] = $userName;
            header("Location: /posts");


        } else {
            header("Location: /login");
        }
    }

}