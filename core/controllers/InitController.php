<?php
namespace App\Controllers;
use App\Boot\ConfigManager;
use App\Entities\Config;
use App\Entities\User;
use App\Helpers\Controller;
use App\Helpers\Database;
use App\Repositories\UserRepository;

/**
 * Class InitController
 * @package App\Controllers
 */
class InitController extends Controller
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function initAction()
    {
        // Check that website is not initialized
        $configManager = new ConfigManager();

        $conf = $configManager->loadConfiguration();
        if (
            isset($conf['website'])
            && isset($conf['website']['initialized'])
            && $conf['website']['initialized']
        ) {
            $this->redirect('/');
            exit;
        }

        $config = new Config($_POST);

        $violations = $config->validate();

        if (!empty($violations)) {
            return $this->render('init/init.html.twig', [
               'config' => $_POST,
               'errors' => $violations
            ]);
        }

        $configuration = $configManager->updateConfig($config);

        // test database connection
        Database::configureDB($configuration['database']);



        try {
            Database::getDB();
        } catch (\PDOException $exception) {
            // Redirect to config if PDO Exception is raised -> can not connect to given database
            $configManager->updateConfig($config, false);
            return $this->render('init/init.html.twig', [
                'errors' => ['Could not connect to database: ' . $exception->getMessage()],
                'config' => $_POST,
            ]);
        }

        // Create Database
        // Get Content of Database Initialization SQL file
        $file = file_get_contents(__DIR__ . '/../binaries/pinkflowers.sql');
        // Init Database
        Database::initDatabase($file);

        $this->redirect('/first-register');
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function firstUserAction()
    {
        $userRepository = new UserRepository();

        if ($userRepository->adminExists()) {
            $this->redirect('Location: /');
        }
        // Check that website is not initialized
        $configManager = new ConfigManager();

        $configManager->loadConfiguration();

        return $this->render('init/user.html.twig');
    }

    /**
     *
     */
    public function createFirstUserAction()
    {
        $userRepository = new UserRepository();
        // If admin already exists, redirect to home page
        if ($userRepository->adminExists()) {
            $this->redirect('Location: /');
        }

        $user = new User($_POST);
        $user->setPassword($_POST['password']);
        $user->generateToken();
        $userRepository->create($user, 1);

        $_SESSION['user'] = [
            'name' => $user->username,
            'token' => $user->token,
            'email' => $user->email
        ];

        $this->redirect('/admin/posts');
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function configureAction()
    {
        // Check that website is not initialized
        $configManager = new ConfigManager();

        $conf = $configManager->loadConfiguration();
        if (
            isset($conf['website'])
            && isset($conf['website']['initialized'])
            && $conf['website']['initialized']
        ) {
            $this->redirect('/');
            exit;
        }
        return $this->render('init/init.html.twig');
    }
}