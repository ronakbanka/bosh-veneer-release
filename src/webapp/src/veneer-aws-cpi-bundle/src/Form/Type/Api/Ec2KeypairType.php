<?php

namespace Veneer\AwsCpiBundle\Form\Type\Api;

use Symfony\Component\Form\AbstractType;

class Ec2KeypairType extends AbstractType
{
    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'veneer_awscpi_api_ec2_keypair';
    }
}
