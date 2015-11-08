<?php

namespace Veneer\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Veneer\CoreBundle\Controller\AbstractController;
use Veneer\CoreBundle\Service\Breadcrumbs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkspaceRepoBlobController extends AbstractController
{
    public function indexAction($ref, $path)
    {
        $path = ltrim($path, '/');
        $repo = $this->container->get('veneer_core.workspace.repository');
        $checkout = $repo->createCheckout($ref);

        return $this->renderApi(
            'VeneerCoreBundle:WorkspaceRepoBlob:index.html.twig',
            [
                'ref' => $ref,
                'path' => $path,
                'data' => $checkout->get($path),
            ],
            [
                'def_nav' => WorkspaceRepoController::defNav($this->container->get('veneer_core.breadcrumbs'), $ref, $path),
            ]
        );
    }
}