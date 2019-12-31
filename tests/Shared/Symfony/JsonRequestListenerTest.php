<?php

namespace OnlyTracker\Tests\Shared\Symfony;

use OnlyTracker\Shared\Infrastructure\Symfony\JsonRequestListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class JsonRequestListenerTest extends TestCase
{
    /** @var \Symfony\Component\HttpKernel\HttpKernelInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $kernel;

    protected function setUp()
    {
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    public function testJsonToArray()
    {
        $content    = '{"key" : ["value1", "value2"]}';
        $listener   = new JsonRequestListener();
        $request    = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);
        $event      = new RequestEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $listener->jsonToArray($event);

        $this->assertSame(['key' => ['value1', 'value2']], $request->request->all());
    }

    public function testInvalidJson()
    {
        $content    = '{"key" : invalid body }';
        $listener   = new JsonRequestListener();
        $request    = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);
        $event      = new RequestEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        try {
            $listener->jsonToArray($event);
            $this->fail('Exception must been thrown');
        } catch (BadRequestHttpException $e) {
            $this->assertStringStartsWith('Invalid json content body: ', $e->getMessage());
        }
    }

    public function testTooDeepJson()
    {
        $content    = '{"key" : [[[[[[[[]]]]]]]] }';
        $listener   = new JsonRequestListener();
        $request    = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], $content);
        $event      = new RequestEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        try {
            $listener->jsonToArray($event);
            $this->fail('Exception must been thrown');
        } catch (BadRequestHttpException $e) {
            $this->assertStringStartsWith('Invalid json content body: ', $e->getMessage());
        }
    }
}
