<?php

declare(strict_types=1);

namespace Effectra\Core\Upload;

class UploadState
{
    public function __construct(
        protected string $file,
        protected bool $uploaded = false,
        protected string $path,
        protected string $input
    ) {
       
    }

    public function toArray( ): array
    {
        return [
            'file' => $this->file,
            'state' => $this->uploaded ? 'uploaded' : 'failed',
            'path' => $this->path,
            'input' => $this->input
        ];
    }
}