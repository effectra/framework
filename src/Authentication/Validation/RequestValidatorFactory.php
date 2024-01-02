<?php

declare(strict_types = 1);

namespace Effectra\Core\Authentication\Validation;

use Effectra\Core\Authentication\Contracts\RequestValidatorFactoryInterface;
use Effectra\Core\Authentication\Contracts\RequestValidatorInterface;
use Psr\Container\ContainerInterface;

class RequestValidatorFactory implements RequestValidatorFactoryInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function make(string $class): RequestValidatorInterface
    {
        $validator = $this->container->get($class);

        if ($validator instanceof RequestValidatorInterface) {
            return $validator;
        }

        throw new \RuntimeException('Failed to instantiate the request validator class "' . $class . '"');
    }
}