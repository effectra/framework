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
/**
 * Class BaseController
 *
 * The base controller class for controllers in the Effectra\Core\Controller namespace.
 * It provides common functionality and dependencies used by controllers.
 */
class BaseController
{
    /**
     * The model associated with the controller.
     *
     * @var ModelInterface|null
     */
    protected ?ModelInterface $model = null;

    /**
     * The cache instance used by the controller.
     *
     * @var CacheInterface|CacheItemPoolInterface
     */
    protected CacheInterface|CacheItemPoolInterface $cache;

    /**
     * The collection object used by the controller.
     *
     * @var \ArrayAccess|\Countable|\IteratorAggregate
     */
    protected \ArrayAccess|\Countable|\IteratorAggregate $collection;

    /**
     * The validator instance used by the controller.
     *
     * @var Validator
     */
    protected Validator $validate;

    /**
     * The logger instance used by the controller.
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $log;

    /**
     * Create a new BaseController instance.
     *
     * Initializes the collection, validator, cache, and logger dependencies.
     */
    public function __construct()
    {
        $this->collection = new Collection();
        $this->validate = new Validator();
        $this->cache = AppCache::getDriver();
        $this->log = Application::log();
    }

    /**
     * Set the model for the controller.
     *
     * @param ModelInterface $model The model instance to set.
     */
    public function setModel(ModelInterface $model): void
    {
        $this->model = $model;
    }
}
