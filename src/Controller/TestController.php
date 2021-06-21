<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController {

    /**
     * Undocumented function
     * @Route("/", name="index") 
     * @return void
     */
    public function hello ()
    {
        return $this->render("hello.html.twig");
    }

    /**
     * Undocumented function
     * @Route("/coucou", name="coucou")
     * @return Response
     */
    public function coucou (): Response
    {
        $info = 'Alexandre';
        return $this->render("coucou.html.twig", [
            "prenom" => $info
        ]);
    }
}