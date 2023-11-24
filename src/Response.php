<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Database\Model;
use Effectra\DataOptimizer\Contracts\DataCollectionInterface;
use Effectra\Http\Extensions\ResponseExtension;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Response
 *
 * Represents an HTTP response with additional functionality provided by ResponseExtension.
 */
class Response extends ResponseExtension
{

    public function json($data, int $status_code = 200, array $headers = []): ResponseInterface
    {
        if ($data instanceof Model) {
            $data = $data->toArray();
        }

        if ($data instanceof DataCollectionInterface) {
            $new = [];
            foreach ($data as $item) {
                $new[] = $item->toArray();
            }
            $data = $new;
        }

        return parent::json($data, $status_code, $headers);
    }
}
