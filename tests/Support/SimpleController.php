<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Nyholm\Psr7\Response;

final class SimpleController
{
    public function action(SimpleRequestModel $request): Response
    {
        return new Response(200, [
            $request->getLogin(),
            $request->getPassword(),
        ]);
    }
}
