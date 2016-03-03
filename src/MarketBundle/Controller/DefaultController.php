<?php

namespace MarketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MarketBundle\Entity\Item;
use MarketBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
            ->add('title')
            ->add('text')
            ->add('category', EntityType::class, array(
                'placeholder' => 'Choose an option',
                'class' => 'MarketBundle:Category',
                'choice_label' => 'name'
            ))
            ->add('price')
            ->add('discount')
            ->add('file')
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($item);
            $em->flush();

            $this->addFlash('notice', 'Item added!');

            return $this->redirectToRoute('admin', array());
        }

        $form2 = $this->createFormBuilder($category)
            ->add('slug')
            ->add('name')

            ->add('save', SubmitType::class)
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
        } elseif ($slug=="discount") {
                $items = $allitems->findDiscount();
        } elseif ($slug) {
            $items = $allitems->findCategory($slug);
        } else {
            $items = $allitems->findAvailable();
        }

        $categories = $this->getDoctrine()
            ->getRepository('MarketBundle:Category')
            ->findAllNonEmpty();

        return array('items'=>$items, 'categories' => $categories);
    }
}
