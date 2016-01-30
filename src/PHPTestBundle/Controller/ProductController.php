<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 30/01/16
 * Time: 17:03
 */

namespace PHPTestBundle\Controller;


use PHPTestBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function createAction(Request $request){

        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'image',
                FileType::class,
                array(
                    'label' => 'Select an image',
                    'required' => false
                )
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'required' => false
                )
            )
            ->add(
                'tags',
                TextType::class,
                array(
                    'label' => "Tags (Please use comma or space to separate tags)"
                )
            )
            ->add(
                'save',
                SubmitType::class,
                array(
                    'label' => 'Add'
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            echo "VALIDA";
            die;
            //return $this->redirectToRoute('task_success');
        }

        return $this->render(
            'PHPTestBundle:full:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}