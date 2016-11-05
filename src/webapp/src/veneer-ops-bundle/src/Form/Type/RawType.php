<?php

namespace Veneer\OpsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use SYmfony\Component\OptionsResolver\OptionsResolverInterface;
use SYmfony\Component\OptionsResolver\Options;
use Veneer\OpsBundle\Form\DataTransformer\RawTransformer;

class RawType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'raw',
                'veneer_core_yaml',
                [
                    'required' => false,
                    'label' => 'Raw Configuration',
                ]
            )
        ;

        $builder->addModelTransformer(new RawTransformer());
    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'veneer_ops_raw';
    }
}