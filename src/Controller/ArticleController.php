<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles')]
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Undocumented function
     * @Route("/saveArticle", name="saveArticle")
     */
    public function saveArticle (Request $request) {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            dump($article);
            $article = $form->getData();
            
            $manager = $this->getDoctrine()->getManager();
            $article->setCreatedAt(new \DateTimeImmutable());
            
            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute("article", [
                "id" => $article->getId(),
            ]);
        }
        return $this->render("article/saveArticle.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $id
     * @Route("/article/{id}", name="article")
     */
    public function article ($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render("article/single.html.twig", [
            "article" => $article
        ]);
    }
}
