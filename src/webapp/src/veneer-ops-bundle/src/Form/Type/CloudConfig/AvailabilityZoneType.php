<?php

namespace Veneer\OpsBundle\Form\Type\CloudConfig;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use SYmfony\Component\OptionsResolver\OptionsResolverInterface;
use Veneer\OpsBundle\Form\DataTransformer\ArrayToYamlTransformer;
use Veneer\OpsBundle\Form\Type\AbstractDeploymentManifestPathType;

class AvailabilityZoneType extends AbstractDeploymentManifestPathType
{
    protected $cpi;

    public function __construct($cpi)
    {
        $this->cpi = $cpi;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                [
                    'label' => 'Name',
                    'veneer_help_html' => '<p>A unique name used to identify and reference the resource pool</p>',
                ]
            )
            ->add(
                'cloud_properties',
                $this->cpi->getDeploymentResourcePoolFormType(),
                [
                    'label' => 'Cloud Properties',
                    'veneer_help_html' => '<p>IaaS-specific properties needed to create VMs.</p>',
                ]
            )
        ;
    }

    public function getName()
    {
        return 'veneer_ops_editor_cloudconfig_availabilityzone';
    }
}
