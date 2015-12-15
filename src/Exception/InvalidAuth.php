<?php

namespace Datto\JsonRpc\Exception;

/**
 * Exception representing an invalid authentication/authorization attempt.
 * The error code corresponds to the JSON-RPC AuthX extension.
 *
 * @author Chad Kosie <ckosie@datto.com>
 */
class InvalidAuth extends \Exception implements \Datto\JsonRpc\Exception
{
    public function __construct()
    {
        parent::__construct('Invalid auth.', -32652);
    }
}
