<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\{Route, Method};

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/contact-us", name="contact.get", methods={"GET"})
     *
     * @return void
     */
    public function contact()
    {
        return $this->render('contact/index.html.twig');
    }
}
