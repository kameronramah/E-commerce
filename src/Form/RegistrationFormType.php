<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;




class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', TextType::class,[
                'attr' => [
                    'placeholder' => 'Votre prénom...',
                    'class' => 'form-control'
                ],
                'label' => false
            ])
            ->add('lastName', TextType::class,[
                'attr' => [
                    'placeholder' => 'Votre nom...',
                    'class' => 'form-control'
                ],
                'label'=> false
            ])
            ->add('street',TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Votre adresse...',
                    'class' => 'form-control'
                ],
                'label'=> false
            ])
            ->add('postalCode', IntegerType::class,[
                'attr' => [
                    'placeholder' => 'Votre code postal...',
                    'class' => 'form-control'
                ],
                'label'=> false
           ])
            ->add('city', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Votre ville...',
                    'class' => 'form-control'
                ],
                'label'=> false
           ])


            ->add('email',  EmailType::class,[
                'attr' => [
                    'placeholder' => 'Votre email...',
                    'class' => 'form-control'
                ],
                'label'=> false
           ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'label'=>'Accepter nos conditions'
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => [
                    'class' => 'password-field'
                    ]],
                'required' => true,
                'first_options'  => ['attr' => [
                    'placeholder' => 'Votre mot de passe...',
                    'class' => 'form-control'
                ],
                    'label' => false
                ],
                'second_options' => ['attr' => [
                    'placeholder' => 'Confirmer votre mot de passe...',
                    'class' => 'form-control'
                ],
                    'label' => false
                ],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
