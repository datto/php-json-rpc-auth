<?php

namespace Datto\JsonRpc\Auth;

use Datto\JsonRpc;
use Datto\JsonRpc\Exception;

/**
 * Implementation of the JsonRpc\Evaluator with pre-execution authentication/authorization.
 *
 * This class wraps around an existing evaluator, and only executes/evaluates a request
 * if the Authenticator allows it.
 *
 * @author Philipp Heckel <ph@datto.com>
 */
class Evaluator implements JsonRpc\Evaluator
{
    /** @var JsonRpc\Evaluator */
    private $evaluator;

    /** @var Authenticator */
    private $authenticator;

    /**
     * Creates an evaluator instance using the given Authenticator.
     *
     * @param JsonRpc\Evaluator $evaluator
     * @param Authenticator $authenticator
     */
    public function __construct(JsonRpc\Evaluator $evaluator, Authenticator $authenticator)
    {
        $this->evaluator = $evaluator;
        $this->authenticator = $authenticator;
    }

    /**
     * Authenticate request and (if successful) map method name to callable
     * and run it with the given arguments.
     *
     * @param string $method Method name
     * @param array $arguments Positional or associative argument array
     * @return mixed Return value of the callable
     * @throws Exception\MissingAuth If the no credentials are given
     * @throws Exception\InvalidAuth If the given credentials are invalid
     */
    public function evaluate($method, $arguments)
    {
        $this->authenticator->authenticate($method, $arguments);
        return $this->evaluator->evaluate($method, $arguments);
    }
}
