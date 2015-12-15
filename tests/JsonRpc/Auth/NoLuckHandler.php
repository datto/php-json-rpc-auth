<?php

namespace Datto\JsonRpc\Auth;

use Datto\JsonRpc;

class NoLuckHandler implements Handler
{
    public function canHandle($method, $arguments)
    {
        return false;
    }

    public function authenticate($method, $arguments)
    {
        return false;
    }
}
