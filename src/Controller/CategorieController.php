<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name:'categories')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/categorie/save', name:"saveCategorie")]
    public function saveCategorie(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $categorie = $form->getData();

           $manager = $this->getDoctrine()->getManager();
           $manager->persist($categorie);
           $manager->flush();

           return $this->redirectToRoute('categorie', [
               'id' => $categorie->getId()
           ]);
        }

        return $this->render('categorie/save.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

    #[Route('/categorie/{id}', name:'categorie')]
    public function single(Categorie $categorie): Response
    {
        // $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        return $this->render("categorie/single.html.twig", [
            'categorie' => $categorie
        ]);
    }
}
