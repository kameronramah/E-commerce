<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Saisir le nom'
                ]
            ])
            ->add('quantityProduct', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'placeholder' => 'Saisir la quantité'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'no-resize',
                    'placeholder' => 'Saisir la description'
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'invalid_message' => 'Veuillez entrer un nombre valide',
                'attr' => [
                    'placeholder' => 'Saisir le prix'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_value' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'multiselect form-select-sm',
                    'placeholder' => 'Sélectionner les catégories'
                ]
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'label' => 'Image',
                'required' => false,
                'multiple' => false,
                'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png'
                            ]
                        ])
                ],
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png',
                ]
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
