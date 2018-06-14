<?php
namespace App\Controllers;
use App\Helpers\Controller;

/**
 * Class FrontController
 * @package App\Controllers
 */
class FrontController extends Controller
{
    /**
     * Display the Home Page of the website on Front Office
     */
    public function homeAction()
    {
        $this->renderFront('home.php');
    }
}