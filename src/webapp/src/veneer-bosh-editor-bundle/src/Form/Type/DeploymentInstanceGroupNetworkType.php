<?php

namespace Veneer\BoshEditorBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class DeploymentInstanceGroupNetworkType extends AbstractDeploymentManifestPathType
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
                'veneer_bosh_editor_deployment_network_manifestselector',
                [
                    'label' => 'Network',
                    'manifest' => $options['manifest'],
                    'manifest_file' => $options['manifest_file'],
                ]
            )
            ->add(
                'static_ips',
                'collection',
                [
                    'label' => 'Static IPs',
                    'type' => 'veneer_core_networking_ip',
                    'required' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            )
        ;
    }

    public function getName()
    {
        return 'veneer_bosh_editor_deployment_instancegroup_network';
    }
}
