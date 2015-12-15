<?php

namespace Datto\JsonRpc;

use Datto\JsonRpc\Auth\Authenticator;
use PHPUnit_Framework_TestCase;

class AuthenticatorTest extends PHPUnit_Framework_TestCase
{
    public function testAddingNewHandlerViaConstructor()
    {
        $handlerMock = $this->getMock('\Datto\JsonRpc\Auth\Handler');

        $auth = new Authenticator(array(
            $handlerMock
        ));

        $this->assertEquals(array($handlerMock), $auth->getHandlers());
    }

    public function testAddingNewHandlerViaAddHandler()
    {
        $handlerMock = $this->getMock('\Datto\JsonRpc\Auth\Handler');

        $auth = new Authenticator();
        $auth->addHandler($handlerMock);

        $this->assertEquals(array($handlerMock), $auth->getHandlers());
    }

    public function testAuthorizingRequestWithValidHandler()
    {
        $handlerMock = $this->getMock('\Datto\JsonRpc\Auth\Handler');
        $handlerMock->expects($this->once())
            ->method('canHandle')
            ->willReturn(true);
        $handlerMock->expects($this->once())
            ->method('authenticate')
            ->willReturn(true);

        $auth = new Authenticator();
        $auth->addHandler($handlerMock);

        $auth->authenticate("", array()); // No exception!
    }

    /**
     * @expectedException \Datto\JsonRpc\Exception\MissingAuth
     */
    public function testAuthorizingRequestWithNoValidHandler()
    {
        $handlerMock = $this->getMock('\Datto\JsonRpc\Auth\Handler');
        $handlerMock->expects($this->once())
            ->method('canHandle')
            ->willReturn(false);
        $handlerMock->expects($this->never())
            ->method('authenticate');

        $auth = new Authenticator();
        $auth->addHandler($handlerMock);

        $auth->authenticate("", array());
    }
}
