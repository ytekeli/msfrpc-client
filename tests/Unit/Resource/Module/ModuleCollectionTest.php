<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Resource\Module;

use Ytekeli\MsfRpcClient\Resource\Module\ExploitModule;
use Ytekeli\MsfRpcClient\Resource\Module\ModuleCollection;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ModuleCollectionTest extends ClientTestCase
{
    public function testModuleCollectionConstruct()
    {
        $moduleCollection = new ModuleCollection('exploit', 'exploits/windows/blabla', $this->clientWithHttpMock([
            $this->createHttpResponsePack($this->fakeLogin()),
            $this->createHttpResponsePack([
                'type'     => 'exploit',
                'name'     => 'Blabla Exploit',
                'fullname' => 'exploits/windows/blabla',
            ]),
            $this->createHttpResponsePack([
                'VERBOSE' => true,
            ]),
        ])->module);

        $collection = $moduleCollection->get();

        $this->assertInstanceOf(ExploitModule::class, $collection);
        $this->assertEquals($collection->type, 'exploit');
        $this->assertEquals($collection->name, 'Blabla Exploit');
        $this->assertEquals($collection->fullname, 'exploits/windows/blabla');
    }

    public function testItFailsWhenCallUnknownModuleMethod()
    {
        $moduleCollection = new ModuleCollection(
            'UNKNOWN_MODULE_TYPE',
            'exploit/aix/local/ibstat_path',
            $this->clientMock([
                'result' => 'fail',
            ])->module
        );

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Unknown module type UNKNOWN_MODULE_TYPE not: exploit, post, encoder, auxiliary, nop, or payload'
        );

        $moduleCollection->get();
    }
}
