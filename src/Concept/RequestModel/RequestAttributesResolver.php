<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionAttribute;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;
use Yiisoft\RequestModel\Attribute\ValueNotFoundException;
use Yiisoft\RequestModel\Concept\RequestModel\Attribute\RequestAttributeInterface;
use Yiisoft\RequestModel\Concept\RequestModel\Attribute\RequestAttributeResolverInterface;

/**
 * @internal
 */
final class RequestAttributesResolver
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @psalm-param list<ReflectionParameter|ReflectionProperty> $reflections
     *
     * @pslam-return array<string, mixed>
     */
    public function resolve(array $reflections, ServerRequestInterface $request): array
    {
        $result = [];
        foreach ($reflections as $reflection) {
            $attributes = $reflection->getAttributes(
                RequestAttributeInterface::class,
                ReflectionAttribute::IS_INSTANCEOF
            );
            foreach ($attributes as $attribute) {
                /** @var RequestAttributeInterface $attributeInstance */
                $attributeInstance = $attribute->newInstance();
                $resolver = $this->container->get($attributeInstance->getResolverClassName());
                if (!$resolver instanceof RequestAttributeResolverInterface) {
                    throw new RuntimeException(
                        sprintf(
                            'Resolver "%s" should implement %s.',
                            $resolver::class,
                            RequestAttributeResolverInterface::class
                        )
                    );
                }

                try {
                    /** @var mixed */
                    $result[$reflection->getName()] = $resolver->resolve($attributeInstance, $request);
                } catch (ValueNotFoundException) {
                }

                if (array_key_exists($reflection->getName(), $result)) {
                    continue 2;
                }
            }
        }
        return $result;
    }
}
