<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $postRep): Response
    {
        $posts = $postRep->findAll();
        return $this->render('post/index.html.twig', compact('posts'));
    }
}
