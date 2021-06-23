<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{

    // public function __construct()
    // {
    //     $this->manager = new EntityManagerInterface;
    // }

    #[Route('/article', name: 'articles')]
    public function index(): Response
    {
        dump($this->getUser());
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        // dump($articles);
        // $articles = $this->getDoctrine()->getRepository(Article::class)->findMonArticle();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Undocumented function
     * @Route("/article/save", name="saveArticle")
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveArticle (Request $request, /* EntityManagerInterface $manager */) 
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            $manager = $this->getDoctrine()->getManager();
            $article->setCreatedAt(new \DateTimeImmutable());
            $image = $article->getImage();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $article->setImage($imageName);
            $image->move(
                $this->getParameter('upload_directory'),
                $imageName
            );
            
            // $manager = new EntityManagerInterface();
            // $manager->persist($article);
            // $manager->flush();
            $manager->persist($article);
            $manager->flush();
            
            $this->addFlash(
                'success',
                'Article bien enregistré!'
            );
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
     * @Route("/article/single/{id}", name="article")
     */
    public function article ($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render("article/single.html.twig", [
            "article" => $article
        ]);
    }

    #[Route("/article/remove/{id}", name:"deleteArticle")]
    public function remove (Article $article) :Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();

        $this->addFlash("danger", "Article supprimé");
        return $this->redirectToRoute("articles");
    }

    #[Route("/article/update/{id}", name:"updateArticle")]
    public function update(Article $article, Request $request) :Response
    {
        $article->setImage(new File($this->getParameter('upload_directory')."/".$article->getImage()));
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute("article", [
                "id" => $article->getId(),
            ]);
        }

        return $this->render("/article/saveArticle.html.twig", [
            "form" => $form->createView(),
            "article" => $article
        ]);
    }
}
