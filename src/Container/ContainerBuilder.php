<?php

namespace Effectra\Container;

use Effectra\Core\Application;

class ContainerBuilder
{
    function __invoke()
    {
        return Application::container();
    }
}