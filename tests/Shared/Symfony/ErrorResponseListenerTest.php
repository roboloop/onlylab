<?php

declare (strict_types = 1);

namespace OnlyTracker\Tests\Shared\Symfony;

use OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\ErrorValidationException;
use OnlyTracker\Shared\Infrastructure\Symfony\ErrorResponseListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ErrorResponseListenerTest extends TestCase
{
    /** @var \Symfony\Component\HttpKernel\HttpKernelInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $kernel;

    protected function setUp()
    {
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    public function testErrorResponse()
    {
        $listener   = new ErrorResponseListener();
        $request    = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json']);
        $exception  = $this->createMock(ErrorValidationException::class);
        $event      = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST, $exception);

        $listener->handle($event);

        $response = $event->getResponse();
        $this->assertNotNull($response);
        $this->assertEquals('{"errors":""}', $response->getContent());
    }
}
