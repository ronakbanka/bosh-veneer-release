<?php

namespace Bosh\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Bosh\WebBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function summaryAction()
    {
        $attributesRaw = $this->container->get('doctrine.orm.bosh_entity_manager')
            ->getRepository('BoshCoreBundle:DirectorAttributes')
            ->findAll();
        $attributes = [];
        
        foreach ($attributesRaw as $attributeRaw) {
            $attributes[$attributeRaw['name']] = $attributeRaw['value'];
        }

        return $this->renderApi(
            'BoshCoreBundle:Index:summary.html.twig',
            [
                'data' => [
                    'name' => $this->container->getParameter('bosh_core.director_name'),
                    'attributes' => $attributes,
                ],
            ],
            [
                'def_nav' => $this->container->get('bosh_core.breadcrumbs'),
            ]
        );
    }
}
