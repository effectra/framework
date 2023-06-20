<?php
/**
 * Created by Stelio Stefanov.
 * stefanov.stelio@gmail.com
 */

namespace Effectra\Core\EncoreProvider\Encore\Exceptions;


use Exception;

/**
 * Class ProcessorException
 * @package Effectra\Core\EncoreProvider\Encore\Exceptions
 */
class ProcessorException extends Exception
{

    public function __construct($message, $code = 0, Exception $previous = null) {

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}