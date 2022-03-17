<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var CommentRepository
     */
    private $commentRepo;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = new PostRepository($doctrine);
        $this->categoryRepo = new CategoryRepository($doctrine);
        $this->commentRepo = new CommentRepository($doctrine);
        $this->entityManager = $doctrine->getManager();
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
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/post/{id}", name="app_post_show", requirements={"id"="\d+"})
     */
    public function show(int $id, Request $request): Response
    {
        $post = $this->postRepo->find($id);
        if ($this->getUser()) {
            /**
             * @var User $user
             */
            $user = $this->getUser();
            $userComment = $this->commentRepo->findOneBy(['post' => $post, 'user' => $user]);
            if (null === $userComment) {
                $userComment = new Comment();
                $userComment->setPost($post);
                $userComment->setUser($user);
                $commentId = false;
            } else {
                $commentId = $userComment->getId();
            }
            $form = $this->createForm(CommentType::class, $userComment);

            $options = [
                'post' => $post,
                'formComment' => $form->createView(),
                'commentId' => $commentId,
            ];
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($userComment);
                $this->entityManager->flush();

                $this->redirectToRoute('app_post_show', ['id' => $id]);
            }
        } else {
            $options = ['post' => $post];
        }

        return $this->render('post/show.html.twig', $options);
    }

    /**
     * @Route("/post.category/{category_id}",name="app_post_by_category",requirements={"category_id"="\d+"})
     */
    public function listByCategory(int $category_id): Response
    {
        $category = $this->categoryRepo->find($category_id);
        $posts = $this->postRepo->findBy(['category' => $category]);

        return $this->render('post/category.html.twig', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }
}
