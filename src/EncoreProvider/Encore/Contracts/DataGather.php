<?php
/**
 * Created by Stelio Stefanov.
 * stefanov.stelio@gmail.com
 */
declare(strict_types=1);

namespace Effectra\Core\EncoreProvider\Encore\Contracts;

/**
 * Interface DataGather
 * @package Effectra\Core\EncoreProvider\Encore\Contracts
 */
interface DataGather
{

    public function load();

    public function getEntryPointsData(): array;

    public function getManifestData(): array;
}