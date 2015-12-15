<?php

namespace Datto\JsonRpc\Auth;

use Datto\JsonRpc;

class TokenHandler implements Handler
{
    public function canHandle($method, $arguments)
    {
        return isset($arguments['token']);
    }

    public function authenticate($method, $arguments)
    {
        return $arguments['token'] === 'spooky password';
    }
}
