<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
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

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->userPostRepo = $doctrine->getRepository(Post::class);
    }

    /**
     * @Route("/profile", name="app_profile")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $userPosts = $this->userPostRepo->findByUser($user);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            // 'user' => $user,
            'userPosts' => $userPosts,
        ]);
    }
}
