<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends Controller
{
    /**
     * @Route("/")
     * @Route("/{path}", requirements={"path"="(login|logout|admin|gallery|image)(\/.*?)*"})
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('frontend.html.twig');
    }
}
