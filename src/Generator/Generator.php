<?php declare(strict_types=1);

namespace Effectra\Core\Generator;

use Effectra\Core\Contracts\GeneratorInterface;

class Generator implements GeneratorInterface
{

    public static function make(string $className,string $savePath,array $option = []):int|false
    {
        
        return false;
    }
}