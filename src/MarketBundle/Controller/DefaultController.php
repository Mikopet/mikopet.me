<?php

namespace MarketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()
            ->getRepository('MarketBundle:Item')
            ->findAll();

        return array('items'=>$items);
    }

    /**
     * @Route("/upload")
     * @Template()
     */
    public function uploadAction()
    {
        return array();
    }
}
