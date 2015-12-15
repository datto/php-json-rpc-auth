<?php

namespace Datto\JsonRpc\Auth;

use Datto\JsonRpc\Exception\InvalidAuth;
use Datto\JsonRpc\Exception\MissingAuth;

/**
 * This class authorizes requests by iterating over the provided authentication handlers,
 * attempting to authorize the request with each handler.
 *
 * @author Chad Kosie <ckosie@datto.com>, Philipp Heckel <ph@datto.com>
 */
class Authenticator
{
    /**
     * @var Handler[]
     */
    private $handlers;

    /**
     * @param Handler[] $handlers
     */
    public function __construct(array $handlers = array())
    {
        $this->handlers = $handlers;
    }

    /**
     * Add an authentication handler to be iterated over when attempting to authorize a request.
     *
     * @param Handler $handler
     */
    public function addHandler(Handler $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Returns all authentication handlers currently attached.
     *
     * @return Handler[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Attempt to authorize a request. This will iterate over all authentication handlers that can handle this type of
     * request. It will stop after it has found one that can authenticate the request.
     *
     * @param string $method JSON-RPC method name
     * @param array $arguments JSON-RPC arguments array (positional or associative)
     * @throws MissingAuth If the no credentials are given
     * @throws InvalidAuth If the given credentials are invalid
     */
    public function authenticate($method, $arguments)
    {
        $handlers = $this->filterHandlers($method, $arguments);

        if (count($handlers) > 0) {
            foreach ($handlers as $handler) {
                $isAuthenticated = $handler->authenticate($method, $arguments);

                if ($isAuthenticated) {
                    return;
                }
            }

            throw new InvalidAuth();
        } else {
            throw new MissingAuth();
        }
    }

    /**
     * Filters the handlers array down to only the handlers that can handle
     * the given request.
     *
     * @param string $method JSON-RPC method name
     * @param array $arguments JSON-RPC arguments array (positional or associative)
     * @return Handler[] Filtered list of handlers
     */
    private function filterHandlers($method, $arguments)
    {
        $handlers = array();

        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($method, $arguments)) {
                $handlers[] = $handler;
            }
        }

        return $handlers;
    }
}
