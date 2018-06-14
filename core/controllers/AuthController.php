<?php

namespace App\Controllers;
use App\Entities\User;
use App\Helpers\Controller;
use App\Repositories\UserRepository;

/**
 * Class AuthController
 * @package App\Controllers
 */
class AuthController extends Controller
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
        // Redirect to posts dashboard if admin is logged in
        if (isset($_SESSION['user'])) {
            $this->redirect('/admin/posts');
        }
        return $this->render('auth/login.html.twig');
    }

    /**
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAccount()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $userName = $_POST['username'];
            $password = $_POST['password'];

            $userRepository = new UserRepository();
            $user = $userRepository->getByUsername($userName);

            if (empty($user)) {
                $error = 'User does not exist';
                return $this->render('auth/login.html.twig', [
                    'credentials' => $_POST,
                    'error' => $error
                ]);
            }

            // Check that passwords match
            $validPassword = $user->checkPassword($password);
            if (!$validPassword) {
                $error = 'Invalid password';
                return $this->render('auth/login.html.twig', [
                    'credentials' => $_POST,
                    'error' => $error,
                ]);
            }

            // Check that user is Admin
            if ($user->role !== User::ROLE_ADMIN) {
                $error = 'Invalid Username and Password';
                return $this->render('auth/login.html.twig', [
                    'credentials' => $_POST,
                    'error' => $error,
                ]);
            }

            /**
             * @var User $user
             */
            $_SESSION['user'] = [
                'name' => $user->username,
                'token' => $user->token,
                'email' => $user->email
            ];
            $this->redirect('/admin/posts');
        } else {
            // rerender
            $error = "Missing password or username";
            return $this->render('auth/login.html.twig', [
                'credentials' => [
                    'username' => $_POST['username'] ?? '',
                    'password' => $_POST['password'] ?? '',
                ],
                'error' => $error,
            ]);
        }
    }

    /**
     * Log out from current session
     */
    public function logOut()
    {
        unset($_SESSION);
        $this->redirect('/admin/login');
    }
}
