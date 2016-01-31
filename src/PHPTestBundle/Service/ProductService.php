<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 31/01/16
 * Time: 14:09.
 */
namespace PHPTestBundle\Service;

use Doctrine\ORM\EntityManager;
use DoctrineExtensions\Taggable\TagManager;
use PHPTestBundle\Entity\Product;
use PHPTestBundle\Repository\ProductRepository;
use Doctrine\ORM\Query\Expr;

/**
 * Class ProductService.
 */
class ProductService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var \PHPTestBundle\Repository\TagRepository
     */
    private $tagRepository;

    /**
     * ProductService constructor.
     *
     * @param EntityManager $entityManager
     * @param TagManager    $tagManager
     */
    public function __construct(EntityManager $entityManager, TagManager $tagManager)
    {
        $this->entityManager = $entityManager;
        $this->tagManager = $tagManager;
    }

    /**
     * Set product repository.
     *
     * @param $entityClass
     */
    public function setProductRepository($entityClass)
    {
        $this->productRepository = $this->entityManager->getRepository($entityClass);
    }

    /**
     * Set tag repository.
     *
     * @param $entityClass
     */
    public function setTagRepository($entityClass)
    {
        $this->tagRepository = $this->entityManager->getRepository($entityClass);
    }

    /**
     * Add a new product.
     *
     * @param Product $product
     * @param $tags
     *
     * @return Product
     */
    public function addProduct(Product $product, $tags)
    {
        return $this->persistProduct($product, $tags);
    }

    /**
     * Update a product.
     *
     * @param Product $product
     * @param $tags
     *
     * @return Product
     */
    public function updateProduct(Product $product, $tags)
    {
        $product->removeTags();

        return $this->persistProduct($product, $tags);
    }

    /**
     * Return all product by Insert date.
     *
     * @param $sort
     *
     * @return array
     */
    public function findAllProductsByInsertDate($sort)
    {
        return $this->productRepository->findAllOrderedByInsertDate($sort);
    }

    /**
     * Find product by tags using LIKE.
     *
     * @param $query
     *
     * @return array
     */
    public function findAllByTag($query)
    {
        $results = $this->tagRepository->findTagsUsingLike($query);

        $ids = array();
        foreach ($results as $result) {
            $ids[] = $result['resourceId'];
        }

        return $this->productRepository->findByIds($ids);
    }

    /**
     * Return product by id.
     *
     * @param $id
     *
     * @return string
     */
    public function findById($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->findOneBy(
            array(
                'id' => $id,
            )
        );
        $tags = $this->entityManager
            ->createQueryBuilder()

            ->select('t')
            ->from('PHPTestBundle:Tag', 't')

            ->innerJoin('t.tagging', 't2', Expr\Join::WITH, 't2.resourceId = :id AND t2.resourceType = :type')
            ->setParameter('id', $product->getTaggableId())
            ->setParameter('type', $product->getTaggableType())

            ->getQuery()
            ->getResult()
        ;
        $product->setTagsAsString(implode(', ', $tags));

        return $product;
    }

    /**
     * Return product name by id.
     *
     * @param $id
     *
     * @return string
     */
    public function getProductNameById($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->findOneBy(
            array(
                'id' => $id,
            )
        );

        return $product->getName();
    }

    /**
     * Persist product (Add or update)
     *
     * @param Product $product
     * @param $tags
     * @return Product
     */
    private function persistProduct(Product $product, $tags)
    {
        $tags = $this->tagManager->splitTagNames($tags);
        foreach ($tags as $tag) {
            $singleTag = $this->tagManager->loadOrCreateTag(trim($tag));
            $this->tagManager->addTag($singleTag, $product);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $this->tagManager->saveTagging($product);

        return $product;
    }
}
