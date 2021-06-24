<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController {

    public function __construct(RequestStack $requestStack)
    {
        // $this->requestStack = $requestStack;
        $this->session = $requestStack->getSession();
    }
    /**
     * Undocumented function
     * @Route("/", name="index", methods={"GET"}) 
     * @return void
     */
    public function hello ()
    {
        $this->session->set("Test", ["toto", "tata"]);
        dump($this->session);
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
            "prenom" => $info,
            "test" => "toto",
            'basket' => $this->session->get("Test", [])
        ]);
    }

    /**
     * @Route("/test/jsonTest", name="jsonTest")
     *
     * @return Response
     */
    public function jsonTest () :Response 
    {
        // return new Response(
            
        //     json_encode("Hello World"),
        //     Response::HTTP_OK,
        //     ['content-type' => 'application/json']
        // );
        return new JsonResponse("Hello world");
    }
}