<?php
/**
 * Created by PhpStorm.
 * User: benoitdelboe
 * Date: 05/08/2016
 * Time: 11:40
 */


namespace Blog\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy'
            ))
            ->add('title', TextType::class)
            ->add('content',TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Create article'));

    }

}