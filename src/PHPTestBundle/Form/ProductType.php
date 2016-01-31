<?php

namespace PHPTestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'imageFile',
                FileType::class,
                array(
                    'label' => 'Select an image',
                    'required' => false,
                )
            )
            ->add(
                'description',
                TextareaType::class,
                array(
                    'required' => false,
                )
            )
            ->add(
                'tags',
                TextType::class,
                array(
                    'label' => 'Tags (Please use the comma to separate tags)',
                    'required' => true,
                )
            )
            ->add(
                'save',
                SubmitType::class,
                array(
                    'label' => 'Add',
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PHPTestBundle\Entity\Product',
        ));
    }
}
