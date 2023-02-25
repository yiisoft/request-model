<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;

interface RequestDataProviderInterface
{
    /**
     * @psalm-return array<string, mixed>
     */
    public function getData(ServerRequestInterface $request): array;
}
