<?php

namespace Smith981\NewsboxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class StoryFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'filter_number_range')
            ->add('title', 'filter_text')
            ->add('subtitle', 'filter_text')
            ->add('author', 'filter_text')
            ->add('created', 'filter_date_range')
            ->add('status', 'filter_number_range')
            ->add('location', 'filter_text')
            ->add('url', 'filter_text')
            ->add('displayColumn', 'filter_number_range')
            ->add('weight', 'filter_number_range')
            ->add('blurb', 'filter_text')
            ->add('text', 'filter_text')
            ->add('issue', 'filter_number_range')
        ;

        $listener = function(FormEvent $event)
        {
            // Is data empty?
            foreach ($event->getData() as $data) {
                if(is_array($data)) {
                    foreach ($data as $subData) {
                        if(!empty($subData)) return;
                    }
                }
                else {
                    if(!empty($data)) return;
                }
            }

            $event->getForm()->addError(new FormError('Filter empty'));
        };
        $builder->addEventListener(FormEvents::POST_BIND, $listener);
    }

    public function getName()
    {
        return 'smith981_newsboxbundle_storyfiltertype';
    }
}
