<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Nyholm\Psr7\Response;
use Yiisoft\RequestModel\Attribute\ParsedBody;
use Yiisoft\RequestModel\Attribute\QueryParam;
use Yiisoft\RequestModel\Attribute\ReqAttribute;
use Yiisoft\RequestModel\Attribute\RouteParam;
use Yiisoft\RequestModel\Attribute\UploadedFiles;

final class SimpleController
{
    public function action(SimpleRequestModel $request): Response
    {
        return new Response(200, [
            $request->getLogin(),
            $request->getPassword(),
        ]);
    }

    public function anotherAction(SimpleRequestModel $request): Response
    {
        return new Response(200, [
            'id' => $request->getId(),
        ]);
    }

    public function actionUsingAttributes(
        #[RouteParam('id')] int $id,
        #[ParsedBody] $body,
        #[UploadedFiles] array $files
    ): Response {
        return new Response(200, [
            'id' => $id,
            'body' => $body,
            'countFiles' => count($files)
        ]);
    }

    public function actionUsingAttributes2(
        #[QueryParam('page')] int $page,
        #[ReqAttribute('attribute')] $attribute,
    ): Response {
        return new Response(200, [
            'page' => $page,
            'attribute' => $attribute,
        ]);
    }
}
