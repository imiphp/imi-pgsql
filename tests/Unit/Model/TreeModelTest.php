<?php

declare(strict_types=1);

namespace Imi\Pgsql\Test\Unit\Model;

use Imi\Pgsql\Test\Model\Tree;
use Imi\Pgsql\Test\Model\TreeWithChildren;
use Imi\Test\BaseTest;

/**
 * @testdox TreeModel
 */
class TreeModelTest extends BaseTest
{
    private array $data = [
        ['id' => '1', 'parentId' => '0', 'name' => 'a'],
        ['id' => '2', 'parentId' => '0', 'name' => 'b'],
        ['id' => '3', 'parentId' => '0', 'name' => 'c'],
        ['id' => '4', 'parentId' => '1', 'name' => 'a-1'],
        ['id' => '5', 'parentId' => '1', 'name' => 'a-2'],
        ['id' => '6', 'parentId' => '4', 'name' => 'a-1-1'],
        ['id' => '7', 'parentId' => '4', 'name' => 'a-1-2'],
        ['id' => '8', 'parentId' => '2', 'name' => 'b-1'],
        ['id' => '9', 'parentId' => '2', 'name' => 'b-2'],
    ];

    /**
     * @testdox getChildIds
     */
    public function testGetChildIds(): void
    {
        $tree = Tree::find(1);
        $this->assertEquals(['4', '5'], $tree->getChildIds());
        $this->assertEquals(['8', '9'], $tree->getChildIds(2));
    }

    /**
     * @testdox getChildrenIds
     */
    public function testGetChildrenIds(): void
    {
        $tree = Tree::find(1);
        $this->assertEquals(['1', '4', '5', '6', '7'], $tree->getChildrenIds(null, true));
        $this->assertEquals(['4', '5', '6', '7'], $tree->getChildrenIds());
        $this->assertEquals(['4', '5'], $tree->getChildrenIds(null, false, 1));
    }

    /**
     * @testdox getChildrenList
     */
    public function testGetChildrenList(): void
    {
        $tree = Tree::find(1);
        $this->assertEquals([
            $this->data[3],
            $this->data[4],
            $this->data[5],
            $this->data[6],
        ], json_decode(json_encode($tree->getChildrenList()), true));
        $this->assertEquals([
            $this->data[3],
            $this->data[4],
        ], json_decode(json_encode($tree->getChildrenList(null, 1)), true));
    }

    /**
     * @testdox getParent
     */
    public function testGetParent(): void
    {
        $tree = Tree::find(1);
        $this->assertEquals($this->data[0], $tree->toArray());
        $this->assertNull($tree->getParent());

        $tree = Tree::find(4);
        $this->assertEquals($this->data[3], $tree->toArray());
        $this->assertEquals($this->data[0], $tree->getParent()->toArray() ?? null);
    }

    /**
     * @testdox getParents
     */
    public function testGetParents(): void
    {
        $tree = Tree::find(6);
        $this->assertEquals([
            $this->data[3],
            $this->data[0],
        ], json_decode(json_encode($tree->getParents()), true));
    }

    /**
     * @testdox getAssocList
     */
    public function testGetAssocList(): void
    {
        $list = TreeWithChildren::getAssocList();
        $this->assertEquals([
            ['id' => '1', 'parentId' => '0', 'name' => 'a', 'children' => [
                ['id' => '4', 'parentId' => '1', 'name' => 'a-1', 'children' => [
                    ['id' => '6', 'parentId' => '4', 'name' => 'a-1-1', 'children' => []],
                    ['id' => '7', 'parentId' => '4', 'name' => 'a-1-2', 'children' => []],
                ]],
                ['id' => '5', 'parentId' => '1', 'name' => 'a-2', 'children' => []],
            ]],
            ['id' => '2', 'parentId' => '0', 'name' => 'b', 'children' => [
                ['id' => '8', 'parentId' => '2', 'name' => 'b-1', 'children' => []],
                ['id' => '9', 'parentId' => '2', 'name' => 'b-2', 'children' => []],
            ]],
            ['id' => '3', 'parentId' => '0', 'name' => 'c', 'children' => []],
        ], json_decode(json_encode($list), true));

        $query = TreeWithChildren::query()->whereIn('id', [2, 8]);
        $list = TreeWithChildren::getAssocList($query);
        $this->assertEquals([
            ['id' => '2', 'parentId' => '0', 'name' => 'b', 'children' => [
                ['id' => '8', 'parentId' => '2', 'name' => 'b-1', 'children' => []],
            ]],
        ], json_decode(json_encode($list), true));
    }
}
