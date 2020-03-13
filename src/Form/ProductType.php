<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom du produit',
                    'class' => 'form-control mb-3'
                    ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Quantité en stock',
                    'class' => 'form-control mb-3'
                    ]
            ])
            ->add('price', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix du produit',
                    'class' => 'form-control mb-3'
                    ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Photo du produit',
                'attr' => [
                    'placeholder' => 'Photo du produit',
                    'class' => 'form-control-file mb-3'
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le produit',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
