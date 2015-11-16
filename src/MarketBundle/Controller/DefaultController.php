<?php

namespace MarketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MarketBundle\Entity\Item;
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
     * @Route("/upload", name="upload")
     * @Template()
     */
    public function uploadAction(Request $request)
    {
        $item = new Item();

        $form = $this->createFormBuilder($item)
            ->add('title', 'text')
            ->add('text', 'textarea')
            ->add('category', 'entity', array(
                'placeholder' => 'Choose an option',
                'class' => 'MarketBundle:Category',
                'choice_label' => 'name',
                'property' => 'category'
            ))
            ->add('price', 'text')
            ->add('file', 'file')
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('upload', array());
        }

        return array('form' => $form->createView());
    }
}
