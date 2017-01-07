<?php

namespace WebCanyon\CronManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WebCanyonCronManagerBundle:Default:index.html.twig');
    }
}
