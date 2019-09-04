<?php

namespace Oilstone\ActOn\Exceptions;

use Exception;

/**
 * Class InvalidActOnParameters
 * @package Oilstone\ActOn\Exceptions
 */
class InvalidActOnParameters extends Exception
{
    /**
     * InvalidActOnParameters constructor.
     */
    public function __construct()
    {
        parent::__construct('Invalid parameters for ActOn configuration', 400);
    }
}