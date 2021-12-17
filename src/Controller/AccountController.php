<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A controller for the user profile
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET","POST"})
     */
    public function edit(HttpFoundationRequest $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Your account is updated successfully');

            return $this->redirectToRoute('app_account');
        }

        return $this->render(
            'account/edit.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

}
