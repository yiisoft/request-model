<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

use Psr\Container\ContainerInterface;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Injector\Injector;

final class ModelHydrator
{
    private Injector $injector;

    public function __construct(
        ContainerInterface $container,
        private PropertyHydratorInterface $propertyHydrator,
    ) {
        $this->injector = new Injector($container);
    }

    /**
     * @psalm-template T
     * @psalm-param class-string<T> $modelClassName
     * @psaml-return T
     */
    public function create(string $modelClassName, ?array $data = null): object
    {
        $model = $this->injector->make($modelClassName);

        if (!empty($data)) {
            $this->hydrate($model, $data);
        }

        return $model;
    }

    public function hydrate(ModelInterface $model, array $data): void
    {
        $model->setRawData($data);
        ArrayHelper::setValue();
        foreach ($data as $name => $value) {
            $this->propertyHydrator->hydrate($model, $name, $value);
        }
    }
}
