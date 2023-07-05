<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product_title', null, [
                'attr' => array(
                    'placeholder' => 'Product name'
                )
            ])
            ->add('product_description', null, [
                'attr' => array(
                    'placeholder' => 'Description'
                )
            ])
            ->add('price', null, [
                'attr' => array(
                    'placeholder' => 'Price'
                )
            ])
            ->add('product_img', FileType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'mapped' => false,
            ])
            ->add('product_rate', null, [
                'attr' => array(
                    'placeholder' => 'Rate'
                )
            ])
            ->add('product_tag' , null, [
                'attr' => array(
                    'placeholder' => 'Tag'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
