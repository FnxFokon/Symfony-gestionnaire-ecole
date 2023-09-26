<?php

namespace App\Form;

use App\Entity\InfoUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InfoUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Input de type text pour le nom
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type text pour le prénom
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type Date pour la date de naissance
            ->add('birthDate', DateType::class, [
                'label' => 'Date de naissance',
                'attr' => ['class' => 'form-control mb-3 datepicker'],
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'widget' => 'choice',
                'input' => 'datetime'
            ])
            // Input de type text pour le téléphone
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type text pour le Addresse
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type text pour le code postale
            ->add('zipCode', TextType::class, [
                'label' => 'Code postale',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type text pour la ville
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-controle mb-3']
            ])
            // Input de type text pour le pays
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-controle mb-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InfoUser::class,
            'attr' => ['class' => 'form']
        ]);
    }
}
