<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
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

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->userPostRepo = $doctrine->getRepository(Post::class);
        $this->userRepo = $doctrine->getRepository(User::class);
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
     *@Route("/profile/edit/{id}",name="app_profile_edit",requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function edit(): Response
    {
        return $this->renderForm('profile/edit.html.twig');
    }
}
