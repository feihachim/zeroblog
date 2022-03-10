<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepo;

    /**
     * @var CategoryRepository
     */
    private $categoryRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = $doctrine->getRepository(Post::class);
        $this->categoryRepo = $doctrine->getRepository(Category::class);
    }

    /**
     * @Route("/post", name="app_post")
     */
    public function index(): Response
    {
        $posts = $this->postRepo->findAll();
        $categories = $this->categoryRepo->findAll();
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/post/{id}", name="app_post_show", requirements={"id"="\d+"})
     * @param integer $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $post = $this->postRepo->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }
}
