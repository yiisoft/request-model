<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;

final class FilesRequestModel extends RequestModel
{
    public function getId(): int
    {
        return (int)$this->getAttributeValue('body.id');
    }

    public function getPhotos(): array
    {
        return $this->getAttributeValue('files.photos');
    }
}
