<?php

namespace App\Form;

use App\Entity\BienImmo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;



class BienImmobilierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('title', TextType::class, [
                'label' => 'Titre de votre annonce',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Titre pour votre annonce'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre titre doit contenir au moins {{ limit }} caractères',
                        'max' => 100,
                        'maxMessage' => 'Votre titre doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('room', TextType::class, [
                'label' => 'Nombre de piece',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Titre pour votre annonce'
                    ]),
                   
                    
                ]
            ])            
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un prix pour votre annonce'
                    ]),
                    
                    
                ]
            ])
            ->add('area',TextType::class, [
                'label' => 'Surface',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une surface pour votre annonce'
                    ]),
                   
                    
                ]
            ])
            ->add('postalCode',TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un code postal pour votre annonce'
                    ]),
                    
                ]
            ])
            ->add("common", TextType::class, [
                'label' => 'Commune',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une commune'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre commune doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre commune doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse : 15 b chemin du clousot',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre adresse doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre adresse doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('typeOfPropriete', ChoiceType::class, [
                'label' => 'Type de transaction',
                'choices'  => [
                    'Vente' => true,
                    'Location' => false,
                ]
            ])            
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter un bien',
                'attr' => [
                    'class' => 'btn btn-outline-primary col-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BienImmo::class,
        
        ]);
    }
}
