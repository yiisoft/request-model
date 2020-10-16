<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://github.com/yiisoft.png" height="100px">
    </a>
    <h1 align="center">Yii Request Model</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/request-model/v/stable.png)](https://packagist.org/packages/yiisoft/request-model)
[![Total Downloads](https://poser.pugx.org/yiisoft/request-model/downloads.png)](https://packagist.org/packages/yiisoft/request-model)
[![Build status](https://github.com/yiisoft/request-model/workflows/build/badge.svg)](https://github.com/yiisoft/request-model/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/request-model/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/request-model/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/request-model/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/request-model/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Frequest-model%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/request-model/master)
[![static analysis](https://github.com/yiisoft/request-model/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/request-model/actions?query=workflow%3A%22static+analysis%22)

Request model is used to simplify working with request data. It allows you to decorate data for easy retrieval and automatically validate it when needed.

## Installation

The package could be installed with composer:

```
composer require yiisoft/request-model
```

## General usage

A simple version of the request model looks like this:

```php 
use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\ValidatableModelInterface;
use Yiisoft\Validator\Rule\Required;

final class AuthRequest extends RequestModel implements ValidatableModelInterface
{
    public function getLogin(): string
    {
        return (string)$this->getValue('body.login');
    }

    public function getPassword(): string
    {
        return (string)$this->getValue('body.password');
    }

    public function getRules(): array
    {
        return [
            'body.login' => [
                new Required(),
            ],
            'body.password' => [
                new Required(),
            ]
        ];
    }
}
```

Usage in controller:

```php
use Psr\Http\Message\ResponseInterface;

final class SimpleController
{
    public function action(SimpleRequestModel $request): ResponseInterface
    {
        echo $request->getLogin();
        ...
    }
}
```

If the data does not pass validation, an exception will be thrown RequestValidationException.
If you need to handle an exception and for example send a response, you can intercept its middleware.

For example:
```php
final class ExceptionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (RequestValidationException $e) {
            return new Response(400, [], $e->getFirstError());
        }
    }
}
```

You can use the request model without validation. To do this, the class is fashionable and you need to remove the ValidatableModelInterface interface.
In this case, the data will also be included in the mod, but will not be validated.
For example:

```php
final class ViewPostRequest extends RequestModel
{
    public function getId(): int
    {
        return (int)$this->getValue('attributes.id');
    }
}
```


## Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```php
./vendor/bin/phpunit
```

## Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```php
./vendor/bin/infection
```

## Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```php
./vendor/bin/psalm
```
