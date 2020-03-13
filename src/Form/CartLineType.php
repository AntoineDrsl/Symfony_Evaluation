<?php

namespace App\Form;

use App\Entity\CartLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Quantité',
                    'class' => 'form-control mb-1'
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier',
                'attr' => ['class' => 'btn btn-primary w-100']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CartLine::class,
        ]);
    }
}
