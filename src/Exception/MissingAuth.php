<?php

namespace Datto\JsonRpc\Exception;

/**
 * Exception representing missing authentication credentials.
 * The error code corresponds to the JSON-RPC AuthX extension.
 *
 * @author Chad Kosie <ckosie@datto.com>
 */
class MissingAuth extends \Exception implements \Datto\JsonRpc\Exception
{
    public function __construct()
    {
        parent::__construct('Missing auth.', -32651);
    }
}

