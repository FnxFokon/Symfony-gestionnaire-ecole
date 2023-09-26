<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Subject;
use App\Form\InfoUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('subjects', EntityType::class, [
                'label' => 'Ses matières:',
                'class' => Subject::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-control mb-3']
            ])
            // TODO On doit imbriquer le formulaire de InfoUserType
            ->add('infoUser', InfoUserType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'form']
        ]);
    }
}
