<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use PhpParser\Node\Expr\Throw_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * Undocumented function
     * @Route("/user/signup", name="signup")
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function signup (Request $request, UserPasswordHasherInterface $passwordHasher) :Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
                $user->setPassword($passwordHasher->hashPassword($user, $request->request->get("user")["password"]["first"]));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush(); 
                return $this->redirectToRoute("articles");
        }

        return $this->render("user/signup.html.twig", [
            'form' => $form->createView()
        ]);
    }

    // public function lightLogin(Request $request, UserPasswordEncoderInterface $encoder) {
    //     $userForm = new User;
    //     $form = $this->createForm(LoginType::class, $userForm);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user = $this->getDoctrine()->getRepository(User::class)->findBy(["email"=>$userForm->getEmail()]);
    //         $encoder->isPasswordValid($user, $userForm->getPassword());
    //         $encoder->
    //     }
    // }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('articles');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
