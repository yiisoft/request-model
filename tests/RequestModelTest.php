<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\Stream;
use Nyholm\Psr7\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\Tests\Support\FilesRequestModel;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestModelTest extends TestCase
{
    public function testGetValueMethod(): void
    {
        $model = $this->createRequestModel();

        $this->assertEquals('login', $model->getLogin());
        $this->assertEquals('login', $model->getAttributeValue('body.login'));
        $this->assertNull($model->getAttributeValue('query.login'));
    }

    public function testHasValueMethod(): void
    {
        $model = $this->createRequestModel();
        $this->assertTrue($model->hasAttribute('body.login'));
        $this->assertFalse($model->hasAttribute('query.login'));
    }

    public function testGetRequestDataMethod(): void
    {
        $this->assertEquals(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password',
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => [],
            ],
            $this
                ->createRequestModel()
                ->getRequestData()
        );
    }

    public function testCustomAttributeDelimiter(): void
    {
        $model = new class () extends RequestModel {
            protected string $attributeDelimiter = '>';
        };

        $model->setRequestData(
            [
                'body' => [
                    'name.primary' => 'mike',
                ],
            ],
        );

        $this->assertEquals('mike', $model->getAttributeValue('body>name.primary'));
    }

    public function testRequestWithFiles()
    {
        $model = $this->createRequestModelWithFiles();
        $this->assertEquals(1, $model->getId());

        $photos = $model->getPhotos();
        $this->assertIsArray($photos);
        $this->assertCount(2, $photos);
        foreach ($photos as $photo) {
            $this->assertInstanceOf(UploadedFileInterface::class, $photo);
        }
    }

    private function createRequestModel(): SimpleRequestModel
    {
        $model = new SimpleRequestModel();
        $model->setRequestData(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password',
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => [],
            ],
        );

        return $model;
    }

    private function createRequestModelWithFiles(): FilesRequestModel
    {
        $stream = Stream::create('test');
        $model = new FilesRequestModel();
        $model->setRequestData(
            [
                'body' => [
                    'id' => 1,
                ],
                'files' => [
                    'photos' => [
                        new UploadedFile($stream, $stream->getSize(), UPLOAD_ERR_OK, 'face.jpg'),
                        new UploadedFile($stream, $stream->getSize(), UPLOAD_ERR_OK, 'face.png'),
                    ],
                ],
            ],
        );

        return $model;
    }
}
