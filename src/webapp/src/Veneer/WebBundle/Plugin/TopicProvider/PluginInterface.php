<?php

namespace Veneer\WebBundle\Plugin\TopicProvider;

use Symfony\Component\HttpFoundation\Request;

interface PluginInterface
{
    public function getTopics(Request $request, $context);
}
