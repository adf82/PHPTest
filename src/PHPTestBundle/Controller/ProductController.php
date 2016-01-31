<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 30/01/16
 * Time: 17:03.
 */
namespace PHPTestBundle\Controller;

use FPN\TagBundle\Entity\TagManager;
use PHPTestBundle\Entity\Product;
use PHPTestBundle\Repository\ProductRepository;
use PHPTestBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController.
 */
class ProductController extends Controller
{
    /**
     * Product create controller.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TagManager $tagManager */
        $tagManager = $this->get('fpn_tag.tag_manager');

        $product = new Product();
        $form = $this->createForm('PHPTestBundle\Form\ProductType', $product);

        $form->handleRequest($request);

        $tags = $form->get('tags')->getData();
        $tags = !is_null($tags) ? $tagManager->splitTagNames($tags) : null;

        if ($form->isSubmitted() && $form->isValid() && $tags) {
            foreach ($tags as $tag) {
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
                'form' => $form->createView(),
            )
        );
    }

    public function listAction(Request $request)
    {
        /** @var ProductService $productService */
        $productService = $this->get('php_test.product');

        /** @var ProductRepository $repository */
        $repository = $this->getDoctrine()->getRepository('PHPTestBundle:Product');

        $query = $request->get('query');
        if (is_null($query)) {
            $products = $productService->findAllProductsByInsertDate('ASC');
        } else {
            $products = $productService->findAllByTag($query);
        }

        return $this->render(
            'PHPTestBundle:full:list.html.twig',
            array(
                'products' => $products,
             )
        );
    }
}
