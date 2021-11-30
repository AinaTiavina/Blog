<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $postRep): Response
    {
        $posts = $postRep->findBy([],['CreatedAt' => 'DESC']);
        
        return $this->render('post/index.html.twig', compact('posts'));
    }

    /**
     * @Route("/create", name="app_create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post;
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("app_home");
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }    
}
