<?php

namespace Datto\API;

use Datto\JsonRpc\Auth\Authenticator;
use Datto\JsonRpc\Auth\NoLuckHandler;
use Datto\JsonRpc\Auth\TokenHandler;
use Datto\JsonRpc\Auth\Evaluator;
use Datto\JsonRpc\Server;
use Datto\JsonRpc\Simple;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testValidAuth()
    {
        $server = $this->createServerWithTokenAuth();
        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "token": "spooky password", "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"result":1}', $result);
    }

    public function testInvalidAuth()
    {
        $server = $this->createServerWithTokenAuth();
        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "token": "INVALID", "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"error":{"code":-32652,"message":"Invalid auth."}}', $result);
    }

    public function testMissingAuth()
    {
        $server = $this->createServerWithTokenAuth();
        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"error":{"code":-32651,"message":"Missing auth."}}', $result);
    }

    public function testNoHandlers()
    {
        $server = new Server(new Evaluator(
            new Simple\Evaluator(),
            new Authenticator(array())
        ));

        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"error":{"code":-32651,"message":"Missing auth."}}', $result);
    }

    public function testNoCapableHandlers()
    {
        $server = new Server(new Evaluator(
            new Simple\Evaluator(),
            new Authenticator(array(
                new TokenHandler(),
                new NoLuckHandler()
            ))
        ));

        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"error":{"code":-32651,"message":"Missing auth."}}', $result);
    }

    public function testSecondHandlerSucceeds()
    {
        $server = new Server(new Evaluator(
            new Simple\Evaluator(),
            new Authenticator(array(
                new NoLuckHandler(),
                new TokenHandler()
            ))
        ));

        $result = $server->reply('{"jsonrpc": "2.0", "method": "math/subtract", "params": { "token": "spooky password", "a": 3, "b": 2 }, "id": 1}');

        $this->assertSame('{"jsonrpc":"2.0","id":1,"result":1}', $result);
    }

    private function createServerWithTokenAuth()
    {
        $authenticator = new Authenticator(array(
            new TokenHandler()
        ));

        $evaluator = new Evaluator(new Simple\Evaluator(), $authenticator);
        return new Server($evaluator);
    }
}

