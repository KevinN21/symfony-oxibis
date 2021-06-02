<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class)
            ->add('job', ChoiceType::class, ['choices' => [
                'Devéloppeur' => 'dev',
                'Professeur' => 'prof',
                'Administateur' => 'admin',
                'Testeur' => 'tester'
            ]])
            ->add('age', IntegerType::class, [
                'disabled' => false
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'id' => 'btn_save'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
