<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Concept;

use Yiisoft\Injector\Injector;
use Yiisoft\RequestModel\Concept\Model\Hydrator\SimpleHydrator;
use Yiisoft\RequestModel\Concept\Model\Populator\Populator;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\Test\Support\Container\SimpleContainer;

final class ConceptTest extends TestCase
{
    public function test1(): void
    {
        $model = new AbcModel();

        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $populator->populate(
            $model,
            ['a' => '1', 'b' => '2', 'c' => '3'],
        );

        $this->assertSame('1', $model->getA());
        $this->assertSame('2', $model->getB());
        $this->assertSame('3', $model->getC());
    }

    public function test2(): void
    {
        $model = new AbcModel();

        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $populator->populate(
            $model,
            ['x' => '1', 'y' => '2', 'z' => '3'],
            ['a' => 'x', 'b' => 'y', 'c' => 'z'],
        );

        $this->assertSame('1', $model->getA());
        $this->assertSame('2', $model->getB());
        $this->assertSame('3', $model->getC());
    }

    public function test3(): void
    {
        $model = new AbcModel();

        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $populator->populate(
            $model,
            ['x' => ['one' => '1'], 'y' => ['two.dot' => '2'], 'z' => '3'],
            ['a' => 'x.one', 'b' => ['y', 'two.dot'], 'c' => 'z'],
        );

        $this->assertSame('1', $model->getA());
        $this->assertSame('2', $model->getB());
        $this->assertSame('3', $model->getC());
    }

    public function test4(): void
    {
        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $model = $populator->createObject(AbcModel::class, ['a' => '1', 'b' => '2', 'c' => '3']);

        $this->assertSame('1', $model->getA());
        $this->assertSame('2', $model->getB());
        $this->assertSame('3', $model->getC());
    }

    public function test5(): void
    {
        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $model = $populator->createObject(
            UserModel::class,
            ['name.first' => 'Mike', 'name' => ['last' => 'Li']]
        );

        $this->assertSame('Mike Li', $model->getName());
    }

    public function test6(): void
    {
        $populator = new Populator(
            new SimpleHydrator(),
            new Injector(new SimpleContainer()),
        );

        $model = $populator->createObject(
            UserModel::class,
            ['fio.first' => 'Mike', 'fio' => ['last' => 'Li']],
            ['name' => 'fio'],
        );

        $this->assertSame('Mike Li', $model->getName());
    }
}
