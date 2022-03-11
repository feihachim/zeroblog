<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
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
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var CommentRepository
     */
    private $commentRepo;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->postRepo = $doctrine->getRepository(Post::class);
        $this->categoryRepo = $doctrine->getRepository(Category::class);
        $this->userRepo = $doctrine->getRepository(User::class);
        $this->commentRepo = $doctrine->getRepository(Comment::class);
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
    public function show(int $id, Request $request): Response
    {

        $post = $this->postRepo->find($id);
        if ($this->getUser()) {
            $user = $this->getUser();
            $userComment = $this->commentRepo->findOneByPostAndUser($post, $user);
            //$editMode = is_bool($userComment);
            $form = $this->createForm(CommentType::class);
            if ($userComment) {
                $commentId = $userComment->getId();
            } else {
                $commentId = false;
            }
            $options = [
                'post' => $post,
                'formComment' => $form->createView(),
                'commentId' => $commentId
            ];
        } else {
            $options = ['post' => $post];
        }
        return $this->render('post/show.html.twig', $options);
    }

    /**
     * @Route("/post.category/{category_id}",name="app_post_by_category",requirements={"category_id"="\d+"})
     * @param integer $category_id
     * @return Response
     */
    public function listByCategory(int $category_id): Response
    {
        $category = $this->categoryRepo->find($category_id);
        $posts = $this->postRepo->findByCategory($category);
        return $this->render('post/category.html.twig', [
            'posts' => $posts,
            'category' => $category
        ]);
    }

    /**
     * @Route("/post.user/{user_id}",name="app_post_by_user",requirements={"user_id"="\d+"})
     * @param integer $user_id
     * @return Response
     */
    public function listByUser(int $user_id): Response
    {
        $user = $this->userRepo->find($user_id);
        $posts = $this->postRepo->findByUser($user);
        return $this->render('post/user.html.twig', [
            'posts' => $posts,
            'user' => $user
        ]);
    }
}
