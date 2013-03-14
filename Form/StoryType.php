<?php

namespace Smith981\NewsboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issue')
            ->add('title')
            ->add('subtitle')
            ->add('author')
//            ->add('created', null, array('attr' => array('style' => 'display:none;')))
            ->add('status', 'choice', array('choices' => array(0 => 'Draft', 1 => 'Published')))
            ->add('location')
//            ->add('url')
            ->add('displayColumn', 'choice', array('choices' => array(1 => 1, 2 => 2, 3 => 3)))
            ->add('weight')
            ->add('blurb')
            ->add('text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Smith981\NewsboxBundle\Entity\Story'
        ));
    }

    public function getName()
    {
        return 'smith981_newsboxbundle_storytype';
    }
}
