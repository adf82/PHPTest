<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 30/01/16
 * Time: 20:17
 */

namespace PHPTestBundle\FormType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Product
 * @package PHPTestBundle\FormType
 */
class Product extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'product_form';
    }
}