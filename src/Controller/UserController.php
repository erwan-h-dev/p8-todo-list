<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/users')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    ) {
    }

    #[Route('/', name: 'user_list')]
    public function listAction()
    {
        return $this->render('user/list.html.twig', ['users' => $this->userRepository->findAll()]);
    }

    #[Route('/create', name: 'user_create')]
    public function createAction(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            // get the value of the checkbox roles in form
            if($form->get('roles')->getData()) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            // get the value of the checkbox roles in form
            if ($form->get('roles')->getData()) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles([]);
            }

            $user->setPassword($hashedPassword);

            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
