<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\EditAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A controller for the user profile
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods="GET")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET","POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
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

    /**
     * @Route("/account/changePassword", name="app_password_change", methods={"GET","POST"});
     * 
     * The user should authenticate again if he checked the remember me before changing password
     * or edit account information
     * @IsGranted("IS_AUTHENTICATED_FULLY") 
     */
    public function changePass(HttpFoundationRequest $request, UserPasswordHasherInterface $hash, 
    EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class, null, ["current_password_is_required" => true]);
        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid() ) {
            #encode the password before migrating it to the database
            $user->setPassword(
                $hash->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $em->flush();

            $this->addFlash('success', 'Your password is changed with success!');

            return $this->redirectToRoute('app_account');
        }
        
        return $this->render('account/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
