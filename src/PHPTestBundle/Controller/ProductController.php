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
        /** @var ProductService $productService */
        $productService = $this->get('php_test.product');

        $product = new Product();
        $form = $this->createForm('PHPTestBundle\Form\ProductType', $product);

        $form->handleRequest($request);
        $tags = $form->get('tags')->getData();

        if ($form->isSubmitted() && $form->isValid() && count($tags) > 0) {
            $productService->addProduct($product, $tags);
            if($product instanceof Product){
                return $this->redirectToRoute('php_test_product_list');
            }else{
                throw new \Exception("Error creating product");
            }

        }

        return $this->render(
            'PHPTestBundle:full:create.html.twig',
            array(
                'success' => isset($success) ? true : false,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Products listing controller.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        /** @var ProductService $productService */
        $productService = $this->get('php_test.product');

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

    /**
     * Product edit controller.
     *
     * @param Request $request
     * @param int     $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TagManager $tagManager */
        $tagManager = $this->get('fpn_tag.tag_manager');
        /** @var ProductService $productService */
        $productService = $this->get('php_test.product');

        /** @var Product $product */
        $product = $productService->findById($id);
        $form = $this->createForm('PHPTestBundle\Form\ProductType', $product);

        $form->handleRequest($request);

        $tags = $form->get('tags')->getData();


        if ($form->isSubmitted() && $form->isValid() && count($tags) > 0) {
            $productService->editProduct($product, $tags);

            if($product instanceof Product){
                return $this->redirectToRoute('php_test_product_list');
            }else{
                throw new \Exception("Error updating product with id ".$id);
            }
        }

        return $this->render(
            'PHPTestBundle:full:edit.html.twig',
            array(
                'form' => $form->createView(),
                'name' => $productService->getProductNameById($id),
            )
        );
    }
}
