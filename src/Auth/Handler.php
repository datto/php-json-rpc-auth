<?php

namespace Datto\JsonRpc\Auth;

/**
 * This class is an authorization handler, it is used to authenticate requests made to the json-rpc server.
 *
 * @author Chad Kosie <ckosie@datto.com>
 */
interface Handler
{
    /**
     * Determines if this handler is capable of authorizing this request.
     *
     * @param array $request
     * @return bool
     */
    public function canHandle($method, $arguments);

    /**
     * Determines if this request is actually authenticated
     *
     * @param array $request
     * @return bool
     */
    public function authenticate($method, $arguments);
}
