<?php

namespace App\Controller;

use App\Entity\Post;
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
        $this->userPostRepo = $doctrine->getRepository(Post::class);
        $this->userRepo = $doctrine->getRepository(User::class);
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/profile/{id}",name="app_profile_show", requirements={"id"="\d+"})
     *
     * @param integer $id
     * @return Response
     */
    public function show(int $id = null): Response
    {
        if ($id === null) {
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
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->redirectToRoute("app_profile_show");
        }
        return $this->renderForm('profile/edit.html.twig', [
            'formProfile' => $form
        ]);
    }
}
