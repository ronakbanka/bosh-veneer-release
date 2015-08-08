<?php

namespace Bosh\CoreBundle\Service\Plugin;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PluginFactory implements PluginFactoryInterface
{
    const OBJECT_SCOPE = [
        'bosh/deployment',
        'bosh/deployment:all',
        'bosh/deployment/property:all',
        'bosh/deployment/property',
        'bosh/deployment/instance',
        'bosh/deployment/instance:all',
        'bosh/deployment/vm',
        'bosh/deployment/vm:all',
        'bosh/deployment/vm/network',
        'bosh/deployment/vm/network:all',
        'bosh/task',
        'bosh/task:all',
        'bosh/release',
        'bosh/release:all',
        'bosh/release/package',
        'bosh/release/package:all',
        'bosh/release/template',
        'bosh/release/template:all',
        'bosh/release/version',
        'bosh/release/version:all',
        'bosh/stemcell',
        'bosh/stemcell:all',
    ];

    protected $container;
    protected $map;

    public function __construct(ContainerInterface $container, array $map)
    {
        $this->container = $container;
        $this->map = $map;
    }

    public function getContext(Request $request, $contextName)
    {
        $context = [];

        foreach ($this->map as $serviceId => $objectContexts) {
            if (!in_array($contextName, $objectContexts)) {
                continue;
            }

            $context = array_merge(
                $context,
                $this->container->get($serviceId)->getContext($request, $contextName)
            );
        }

        return $context;
    }

    public function getEndpoints($contextName, array $context = [])
    {
        $endpoints = [];

        foreach ($this->map as $serviceId => $objectContexts) {
            if (!in_array($contextName, $objectContexts)) {
                continue;
            }

            $endpoints = array_merge(
                $endpoints,
                $this->container->get($serviceId)->getEndpoints($contextName, $context)
            );
        }

        $router = $this->container->get('router');

        return array_map(
            function ($v) use ($router) {
                return $router->generate($v[0], $v[1]);
            },
            array_filter(
                $endpoints,
                function ($v) {
                    return null !== $v;
                }
            )
        );
    }

    public function getUserPrimaryLinks($contextName, array $context = [])
    {
        $links = [];

        foreach ($this->map as $serviceId => $objectContexts) {
            if (!in_array($contextName, $objectContexts)) {
                continue;
            }

            foreach ($this->container->get($serviceId)->getUserPrimaryLinks($contextName, $context) as $k => $v) {
                $links[$k][] = $v;
            }
        }

        $links = array_map(
            function (array $v) {
                return $v[count($v) - 1];
            },
            $links
        );

        usort(
            $links,
            function (array $a, array $b) {
                if ($a['priority'] == $b['priority']) {
                    return 0;
                }

                return $a['priority'] < $b['priority'] ? -1 : 1;
            }
        );

        $router = $this->container->get('router');

        return array_values(
            array_map(
                function (array $v) use ($router) {
                    $v['url'] = $router->generate($v['route'][0], $v['route'][1]);
                },
                $links
            )
        );
    }

    public function getUserReferenceLinks($scope, array $context = [])
    {

    }
}
