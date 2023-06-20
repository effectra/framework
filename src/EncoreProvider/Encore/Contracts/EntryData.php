<?php
/**
 * Created by Stelio Stefanov.
 * stefanov.stelio@gmail.com
 */

namespace Effectra\Core\EncoreProvider\Encore\Contracts;

use Effectra\Core\EncoreProvider\Encore\Integrity;

/**
 * Interface EntryData
 * @package Effectra\Core\EncoreProvider\Encore\Contracts
 */
interface EntryData
{

    public function getEntryFiles(): array;

    public function getIntegrity(): Integrity;
}