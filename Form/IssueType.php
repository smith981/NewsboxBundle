<?php

namespace Smith981\NewsboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('created')
            ->add('imageUrl', 'textarea', array('label' => 'Image URL'))
            //->add('publishedDate')
            ->add('status', 'choice', array('choices' => array(0 => 'Draft', 1 => 'Published')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Smith981\NewsboxBundle\Entity\Issue'
        ));
    }

    public function getName()
    {
        return 'smith981_newsboxbundle_issuetype';
    }
}
