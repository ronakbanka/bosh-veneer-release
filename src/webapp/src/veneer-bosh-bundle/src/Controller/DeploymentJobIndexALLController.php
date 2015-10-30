<?php

namespace Veneer\BoshBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query\Expr;
use Veneer\CoreBundle\Controller\AbstractController;
use Veneer\CoreBundle\Service\Breadcrumbs;

class DeploymentJobIndexALLController extends AbstractController
{
    public static function defNav(Breadcrumbs $nav, $_bosh)
    {
        return DeploymentJobController::defNav($nav, $_bosh)
            ->add(
                'index',
                [
                    'veneer_bosh_deployment_job_indexALL_index' => [
                        'deployment' => $_bosh['deployment']['name'],
                        'job' => $_bosh['job']['job'],
                    ],
                ]
            )
        ;
    }

    public function indexAction($_bosh)
    {
        return $this->renderApi(
            'VeneerBoshBundle:DeploymentJobIndexALL:index.html.twig',
            [
                'results' => array_map(
                    function ($v) {
                        return $v
                            ->setSerializationHint('vm', false)
                            ;
                    },
                    $this->container->get('doctrine.orm.bosh_entity_manager')
                        ->getRepository('VeneerBoshBundle:Instances')
                        ->createQueryBuilder('i')
                        ->join('i.vm', 'v')
                        ->where(new Expr\Comparison('i.deployment', '=', ':deployment'))->setParameter('deployment', $_bosh['deployment'])
                        ->andWhere(new Expr\Comparison('i.job', '=', ':job'))->setParameter('job', $_bosh['job']['job'])
                        ->orderBy('i.index', 'ASC')
                        ->getQuery()
                        ->getResult()
                ),
            ],
            [
                'def_nav' => static::defNav($this->container->get('veneer_bosh.breadcrumbs'), $_bosh),
            ]
        );
    }
}