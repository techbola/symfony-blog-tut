<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/custom/{name?}", name="custom")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function custom(Request $request)
    {
        $name = $request->get('name');

        return $this->render('main/show.html.twig', [
            'name' => $name
        ]);
    }
}
