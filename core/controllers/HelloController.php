<?php

namespace App\Controllers;
/**
 * Class HelloController
 */
class HelloController extends BaseController {

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function helloAction() {

        return $this->render('hello.html.twig', ['name' => 'Masselot']);
    }

    /**
     * @param $name
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function helloCustomAction($name) {
        return $this->render('hello.html.twig', ['name' => $name]);
    }
}
