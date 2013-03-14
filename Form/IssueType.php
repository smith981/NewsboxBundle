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
            ->add('created')
            ->add('imageUrl')
            ->add('publishedDate')
            ->add('status')
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
