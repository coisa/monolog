<?php

/**
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Middleware;

use CoiSA\Monolog\Middleware\LoggerAwareMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerAwareMiddlewareTest
 *
 * @package CoiSA\Monolog\Test\Middleware
 */
final class LoggerAwareMiddlewareTest extends TestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var LoggerAwareMiddleware */
    private $middleware;

    public function setUp(): void
    {
        $this->logger         = $this->prophesize(LoggerInterface::class);
        $this->serverRequest  = $this->prophesize(ServerRequestInterface::class);
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->response       = $this->prophesize(ResponseInterface::class);

        $this->middleware = new LoggerAwareMiddleware($this->logger->reveal());

        $this->requestHandler->willImplement(LoggerAwareInterface::class);

        $this->requestHandler->handle($this->serverRequest->reveal())->shouldBeCalledOnce()->will([$this->response, 'reveal']);
        $this->requestHandler->setLogger($this->logger->reveal())->shouldBeCalledOnce();
    }

    public function testConstructWithWrongArgumentRaiseTypeError(): void
    {
        $this->requestHandler->handle($this->serverRequest->reveal())->shouldNotBeCalled();
        $this->requestHandler->setLogger($this->logger->reveal())->shouldNotBeCalled();

        $this->expectException(\TypeError::class);
        new LoggerAwareMiddleware(new \stdClass());
    }

    public function testProcessReturnRequestHandlerResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->requestHandler->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($this->response->reveal(), $response);
    }
}
