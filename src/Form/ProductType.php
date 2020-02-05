<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\TagsInputType;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('price', MoneyType::class, [
                'divisor' => 100
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Choisir une image',
                ],
            ])
            ->add('category')
            ->add('user')
            ->add('tags', TagsInputType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
