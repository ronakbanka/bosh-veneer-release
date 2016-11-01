<?php

namespace Veneer\BoshBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Veneer\BoshBundle\Form\Type\JobRestartType;
use Veneer\CoreBundle\Controller\AbstractController;
use Veneer\CoreBundle\Service\Breadcrumbs;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class DeploymentInstanceGroupInstanceController extends AbstractController
{
    public static function defNav(Breadcrumbs $nav, $_bosh)
    {
        return DeploymentInstanceGroupInstanceALLController::defNav($nav, $_bosh)
            ->add(
                $_bosh['instance']['uuid'],
                [
                    'veneer_bosh_deployment_instancegroup_instance_summary' => [
                        'deployment' => $_bosh['deployment']['name'],
                        'instance_group' => $_bosh['instance_group']['job'],
                        'instance' => $_bosh['instance']['uuid'],
                    ],
                ]
            )
        ;
    }

    public function summaryAction($_bosh)
    {
        return $this->renderApi(
            'VeneerBoshBundle:DeploymentInstanceGroupInstance:summary.html.twig',
            [
                'data' => $_bosh['instance'],
            ],
            [
                'def_nav' => static::defNav($this->container->get('veneer_bosh.breadcrumbs'), $_bosh),
            ]
        );
    }
    
    public function vmAction($_bosh, $_format)
    {
        if (!$_bosh['instance']['vm']) {
            throw new NotFoundHttpException();
        }
        
        return $this->redirectToRoute(
            'veneer_bosh_deployment_vm_summary',
            [
                'deployment' => $_bosh['deployment']['name'],
                'agent' => $_bosh['instance']['vm']['agentId'],
                '_format' => $_format,
            ]
        );
    }
    public function restartAction(Request $request, array $_bosh)
    {
        $form = $this->container->get('form.factory')->createNamed(
            null,
            new JobRestartType(),
            null,
            [
                'csrf_protection' => false,
            ]
        );

        $form->bind($request);

        if (Request::METHOD_POST == $request->getMethod()) {
            if (!$form->isValid()) {
                throw new HttpException(400);
            }

            $payload = $form->getData();

            $state = $this->container->get('doctrine.orm.state_entity_manager')
                ->getRepository('VeneerBoshBundle:DeploymentWorkspace')
                ->findOneBy([
                    'deployment' => $_bosh['deployment']['name'],
                ]);

            $task = $this->container->get('veneer_bosh.api')->sendForTaskId(
                new GuzzleRequest(
                    'PUT',
                    sprintf(
                        '/deployments/%s/jobs/%s%s?%s',
                        $_bosh['deployment']['name'],
                        $_bosh['instance_group']['job'],
                        isset($_bosh['instance']) ? ('/' . $_bosh['instance']['uuid']) : '',
                        http_build_query([
                            'state' => 'restart',
                            'skip_drain' => $payload['skip_drain'] ? 'true' : 'false',
                        ])
                    ),
                    [
                        'content-type' => 'text/yaml',
                    ],
                    $this->container->get('veneer_core.workspace.repository')->showFile(
                        dirname($state->getSourcePath()) . '/.' . basename($state->getSourcePath()),
                        'master'
                    )
                )
            );

            return $this->redirectToRoute(
                'veneer_bosh_task_summary',
                [
                    'task' => $task,
                ]
            );
        }

        return $this->renderApi(
            'VeneerBoshBundle:DeploymentInstanceGroupInstance:restart.html.twig',
            [
                'form' => $form->createView(),
            ],
            [
                'def_nav' => static::defNav($this->container->get('veneer_bosh.breadcrumbs'), $_bosh)
                    ->add(
                        'Restart',
                        [
                            'veneer_bosh_deployment_instancegroup_instance_restart' => [
                                'deployment' => $_bosh['deployment']['name'],
                                'instance_group' => $_bosh['instance_group']['job'],
                                'instance' => $_bosh['instance']['uuid'],
                            ],
                        ]
                    ),
            ]
        );
    }

    public function recreateAction(Request $request, array $_bosh)
    {
        $form = $this->container->get('form.factory')->createNamed(
            null,
            new JobRestartType(),
            null,
            [
                'csrf_protection' => false,
            ]
        );

        $form->bind($request);

        if (Request::METHOD_POST == $request->getMethod()) {
            if (!$form->isValid()) {
                throw new HttpException(400);
            }

            $payload = $form->getData();

            $state = $this->container->get('doctrine.orm.state_entity_manager')
                ->getRepository('VeneerBoshBundle:DeploymentWorkspace')
                ->findOneBy([
                    'deployment' => $_bosh['deployment']['name'],
                ]);

            $task = $this->container->get('veneer_bosh.api')->sendForTaskId(
                new GuzzleRequest(
                    'PUT',
                    sprintf(
                        '/deployments/%s/jobs/%s%s?%s',
                        $_bosh['deployment']['name'],
                        $_bosh['instance_group']['job'],
                        isset($_bosh['instance']) ? ('/' . $_bosh['instance']['uuid']) : '',
                        http_build_query([
                            'state' => 'recreate',
                            'skip_drain' => $payload['skip_drain'] ? 'true' : 'false',
                        ])
                    ),
                    [
                        'content-type' => 'text/yaml',
                    ],
                    $this->container->get('veneer_core.workspace.repository')->showFile(
                        dirname($state->getSourcePath()) . '/.' . basename($state->getSourcePath()),
                        'master'
                    )
                )
            );

            return $this->redirectToRoute(
                'veneer_bosh_task_summary',
                [
                    'task' => $task,
                ]
            );
        }

        return $this->renderApi(
            'VeneerBoshBundle:DeploymentInstanceGroupInstance:recreate.html.twig',
            [
                'form' => $form->createView(),
            ],
            [
                'def_nav' => static::defNav($this->container->get('veneer_bosh.breadcrumbs'), $_bosh)
                    ->add(
                        'Recreate',
                        [
                            'veneer_bosh_deployment_instancegroup_instance_recreate' => [
                                'deployment' => $_bosh['deployment']['name'],
                                'instance_group' => $_bosh['instance_group']['job'],
                                'instance' => $_bosh['instance']['uuid'],
                            ],
                        ]
                    ),
            ]
        );
    }
}