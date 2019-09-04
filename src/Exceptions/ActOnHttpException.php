<?php

namespace Oilstone\ActOn\Exceptions;

use Exception;
use Throwable;

/**
 * Class ActOnHttpException
 * @package Oilstone\ActOn\Exceptions
 */
class ActOnHttpException extends Exception
{
    /**
     * ActOnHttpException constructor.
     * @param Throwable $previous
     */
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('Error submitting data to ActOn', 400, $previous);
    }
}