<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterResolverInterface;
use Yiisoft\RequestModel\Tests\Support\MockAttribute;
use Yiisoft\RequestModel\Tests\Support\TestCase;

abstract class ResolverTestCase extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testResolve(
        HandlerParameterAttributeInterface $attribute,
        ServerRequestInterface $request,
        mixed $expected
    ): void {
        $resolver = $this->getResolver();

        $result = $resolver->resolve($attribute, $request);
        $this->assertEquals($expected, $result);
    }

    public function testWrongAttribute(): void
    {
        $resolver = $this->getResolver();

        $this->expectException(\InvalidArgumentException::class);

        $resolver->resolve(new MockAttribute(), $this->createMock(ServerRequestInterface::class));
    }

    abstract public function dataProvider(): array;

    abstract public function getResolver(): HandlerParameterResolverInterface;
}
