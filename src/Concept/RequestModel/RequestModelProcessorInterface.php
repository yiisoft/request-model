<?php
declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel;

interface RequestModelProcessorInterface
{
    public function process(RequestModelInterface $model): void;
}
