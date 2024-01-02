<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;


interface RedirectInterface
{
    public function getCallbackUrl() :string;
    public function getSuccessUrl():string;
}