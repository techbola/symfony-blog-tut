<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {

        $posts = $postRepository->findAll();
//        dump($posts);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        $form->getErrors();

        if ($form->isSubmitted()){
            
            $em = $this->getDoctrine()->getManager();

            // dump($request->files);

            $file = $request->files->get('post')['attachment'];

            if ($file) {
                
               $filename = $fileUploader->uploadFile($file);

                $post->setImage($filename);
                $em->persist($post);
                $em->flush();
            }

            $this->addFlash('success', 'Post added!');
            return $this->redirect($this->generateUrl('post.index'));
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        // $post = $postRepository->findPostWithCategory($id);

        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Post removed!');

        return $this->redirect($this->generateUrl('post.index'));
    }
}
