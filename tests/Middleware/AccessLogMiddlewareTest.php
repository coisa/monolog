<?php declare(strict_types=1);
/*
 * This file is part of coisa/monolog.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the Apache v2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Monolog\Test\Middleware;

use CoiSA\Monolog\Middleware\AccessLogMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AccessLogMiddlewareTest
 *
 * @package CoiSA\Monolog\Test\Middleware
 */
final class AccessLogMiddlewareTest extends TestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var AccessLogMiddleware */
    private $middleware;

    public function setUp(): void
    {
        $this->logger         = $this->prophesize(LoggerInterface::class);
        $this->serverRequest  = $this->prophesize(ServerRequestInterface::class);
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->response       = $this->prophesize(ResponseInterface::class);

        $this->middleware = new AccessLogMiddleware($this->logger->reveal());
    }

    public function testConstructWithWrongArgumentRaiseTypeError(): void
    {
        $this->expectException(\TypeError::class);
        new AccessLogMiddleware(new \stdClass());
    }

    public function testProcessReturnRequestHandlerResponse(): void
    {
        $this->logger->info(Argument::type('string'), Argument::type('array'))->shouldBeCalledOnce();
        $this->requestHandler->handle($this->serverRequest->reveal())->shouldBeCalledOnce()->will([$this->response, 'reveal']);

        $response = $this->middleware->process($this->serverRequest->reveal(), $this->requestHandler->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($this->response->reveal(), $response);
    }
}
