<?php

namespace App\Controllers;


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

}