<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model;

final class ModelPopulator
{
    public function __construct(
        private ModelHydratorInterface $hydrator,
    ) {
    }

    /**
     * @psalm-param array<string,mixed> $data
     */
    public function populate(ModelInterface $model, array $data): void
    {
        $this->hydrator->hydrate($model, $data);
        $model->setRawData($data);
    }
}
