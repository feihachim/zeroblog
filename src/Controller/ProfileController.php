<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $userPostRepo;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->userPostRepo = new PostRepository($doctrine);
        $this->userRepo = new UserRepository($doctrine);
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/profile/{id}",name="app_profile_show", requirements={"id"="\d+"})
     *
     * @param int $id
     */
    public function show(int $id = null): Response
    {
        if (null === $id) {
            $user = $this->getUser();
        } else {
            $user = $this->userRepo->find($id);
        }
        $userPosts = $this->userPostRepo->findBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'userPosts' => $userPosts,
        ]);
    }

    /**
     * @Route("/profile/edit/",name="app_profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            // Modify password
            /**
             * @var string
             */
            $newPassword = $form->get('newPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $newPassword
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->redirectToRoute('app_profile_show');
        }

        return $this->renderForm('profile/edit.html.twig', [
            'formProfile' => $form,
        ]);
    }
}
