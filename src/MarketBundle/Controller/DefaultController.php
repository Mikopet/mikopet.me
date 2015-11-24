<?php

namespace MarketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MarketBundle\Entity\Item;
use MarketBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     * @Template()
     */
    public function adminAction(Request $request)
    {
        $item = new Item();
        $category = new Category();

        $em = $this->getDoctrine()->getManager();

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
            $em->persist($item);
            $em->flush();

            $this->addFlash('notice', 'Item added!');

            return $this->redirectToRoute('admin', array());
        }

        $form2 = $this->createFormBuilder($category)
            ->add('slug', 'text')
            ->add('name', 'text')

            ->add('save', 'submit')
            ->getForm();

        $form2->handleRequest($request);

        if ($form2->isValid() && $form2->isSubmitted()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('notice', 'Category added!');

            return $this->redirectToRoute('admin', array());
        }

        return array('form' => $form->createView(), 'form2' => $form2->createView());
    }

    /**
     * @Route("/{slug}", name="index")
     * @Template()
     */
    public function indexAction($slug="")
    {
        $allitems = $this->getDoctrine()
            ->getRepository('MarketBundle:Item');
        if ($slug=="free") {
            $items = $allitems->findFree();
        } elseif ($slug) {
            $items = $allitems->findCategory($slug);
        } else {
            $items = $allitems->findAll();
        }

        $categories = $this->getDoctrine()
            ->getRepository('MarketBundle:Category')
            ->findAllNonEmpty();

        return array('items'=>$items, 'categories' => $categories);
    }
}
