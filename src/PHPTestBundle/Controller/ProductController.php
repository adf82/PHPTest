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
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function createAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $tagManager = $this->get('fpn_tag.tag_manager');

        $product = new Product();
        $form = $this->createForm('PHPTestBundle\Form\ProductType', $product);

        $form->handleRequest($request);

        $tags = $form->get('tags')->getData();
        $tags = !is_null($tags) ? explode(',', $tags) : null;

        if ($form->isSubmitted() && $form->isValid() && $tags) {
            foreach($tags as $tag){
                $singleTag = $tagManager->loadOrCreateTag(trim($tag));
                $tagManager->addTag($singleTag, $product);
            }

            $em->persist($product);
            $em->flush();

            $tagManager->saveTagging($product);
            $success = true;
        }

        return $this->render(
            'PHPTestBundle:full:create.html.twig',
            array(
                'success' => isset($success) ? true : false,
                'form' => $form->createView()
            )
        );
    }
}