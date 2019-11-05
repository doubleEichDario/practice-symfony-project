<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TaskType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder -> add('title', TextType::class, ['label' => 'Título'])
             -> add('content', TextareaType::class, ['label' => 'Contenido'])
             -> add('priority', ChoiceType::class, [
                                                  'label' => 'Prioridad',
                                                  'choices' => [
                                                    'Baja' => 'Baja',
                                                    'Media' => 'Media',
                                                    'Alta' => 'Alta'
                                                  ]
                                                ])
             -> add('hours', NumberType::class, ['html5' => true, 'label' => 'Horas' ])
             -> add('submit', SubmitType::class, ['label' => 'Guardar']);
  }
}
