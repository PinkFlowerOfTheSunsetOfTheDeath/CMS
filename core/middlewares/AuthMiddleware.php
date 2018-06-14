<?php
namespace App\Middlewares;
use App\Repositories\UserRepository;

class AuthMiddleware
{
    /**
     * @param $path
     * @return bool
     */
    public function middlewareCheck($path)
    {
        $containsAdmin = strpos($path, '/admin/');
        $isLogin = $path === '/admin/login';
        return $containsAdmin !== false && !$isLogin;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        // If user not found in session
        if (
            !isset($_SESSION['user'])
            || !isset($_SESSION['user']['name'])
            || !isset($_SESSION['user']['token'])
        ) {
            header('Location: /404');
            exit;
        }

        // get username from DB
        $userRepository = new UserRepository();
        $user = $userRepository->getByUsername($_SESSION['user']['name']);

        if (
            !$user->isAdmin()
            || !$user->isTokenValid($_SESSION['user']['token'])
        ) {
            header('Location: /404');
            exit;
        }

        return true;
    }
}