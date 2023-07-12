<?php

declare(strict_types=1);

namespace Effectra\Core\Controller;

use Effectra\Core\Application;
use Effectra\Core\Cache\AppCache;
use Effectra\Core\Utils\Collection;
use Effectra\Core\Contracts\ModelInterface;
use Effectra\Core\Validator;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

class BaseController
{
    protected ?ModelInterface $model = null;

    protected CacheInterface|CacheItemPoolInterface $cache;

    protected  \ArrayAccess|\Countable|\IteratorAggregate $collection;

    protected Validator $validate;

    protected LoggerInterface $log;

    public function __construct()
    {
        $this->collection = new Collection();
        $this->validate = new Validator();
        $this->cache = AppCache::getDriver();
        $this->log = Application::log();
    }

    public function setModel(ModelInterface $model): void
    {
        $this->model = $model;
    }
}
