<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
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
[![type-coverage](https://shepherd.dev/github/yiisoft/request-model/coverage.svg)](https://shepherd.dev/github/yiisoft/request-model)

Request model simplifies working with request data. It allows you to decorate data for easy retrieval and automatically
validate it when needed.

## Installation

The package could be installed with composer:

```
composer require yiisoft/request-model --prefer-dist
```

## General usage

A simple version of the request model looks like the following:

```php
use Yiisoft\RequestModel\RequestModel;
use Yiisoft\Validator\RulesProviderInterface;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Rule\Required;

final class AuthRequest extends RequestModel implements RulesProviderInterface
{
    public function getLogin(): string
    {
        return (string)$this->getAttributeValue('body.login');
    }

    public function getPassword(): string
    {
        return (string)$this->getAttributeValue('body.password');
    }

    public function getRules(): array
    {
        return [
            'body.login' => [
                new Required(),
                new Email(),
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
    public function action(AuthRequest $request): ResponseInterface
    {
        echo $request->getLogin();
        ...
    }
}
```

If the data does not pass validation, `RequestValidationException` will be thrown.
If you need to handle an exception and, for example, send a response, you can intercept its middleware.

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

You can use the request model without validation. To do this, you need to remove the `ValidatableModelInterface`.
In this case, the data will be included into the model, but will not be validated. For example:

```php
final class ViewPostRequest extends RequestModel
{
    public function getId(): int
    {
        return (int)$this->getAttributeValue('router.id');
    }
}
```

Inside the request model class, data is available using the following keys:

| key        | source                        |
|------------|-------------------------------|
| query      | $request->getQueryParams()    |
| body       | $request->getParsedBody()     |
| attributes | $request->getAttributes()     |
| headers    | $request->getHeaders()        |
| files      | $request->getUploadedFiles()  |
| cookie     | $request->getCookieParams()   |
| router     | $currentRoute->getArguments() |

This data can be obtained as follows 

```php
$this->requestData['router']['id'];
```

or through the methods

```php
$this->hasAttribute('body.user_id');
$this->getAttributeValue('body.user_id');
```

#### Attributes

You can use attributes in an action handler to get data from a request:

```php
use Psr\Http\Message\ResponseInterface;
use Yiisoft\RequestModel\Attribute\Route;

final class SimpleController
{
    public function action(#[Route('id')] int $id, #[ReqAttribute('foo')] $attribute,): ResponseInterface
    {
        echo $id;
        //...
    }
}
```

Attributes are also supported in closure actions.

There are several attributes out of the box:

| Name          | Source                    |
|---------------|---------------------------|
| Body          | Parsed body of request    |
| Query         | Query parameter of URI    |
| Request       | Attribute of request      |
| Route         | Argument of current route |
| UploadedFiles | Uploaded files of request |

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Request Model is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
