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

use Ytekeli\MsfRpcClient\Resource\Module\ModuleResource;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ModuleResourceTest extends ClientTestCase
{
    public $info;

    public $options;

    public $module;

    public function setUp(): void
    {
        parent::setUp();

        $this->info = collect([
            'type'     => 'exploit',
            'fullname' => 'unix/ftp/vsftpd_234_backdoor',
        ]);

        $this->options = [
            'option1' => [
                'type'     => 'bool',
                'required' => false,
                'advanced' => true,
                'evasion'  => false,
                'desc'     => 'option1 desc',
                'default'  => false,
            ],
            'option2' => [
                'type'     => 'integer',
                'required' => true,
                'advanced' => false,
                'evasion'  => false,
                'desc'     => 'option2 desc',
            ],
            'option3' => [
                'type'     => 'string',
                'required' => true,
                'advanced' => false,
                'evasion'  => false,
                'desc'     => 'option3 desc',
                'default'  => '/tmp',
            ],
            'option4' => [
                'type'     => 'integer',
                'required' => false,
                'advanced' => true,
                'evasion'  => false,
                'desc'     => 'option4 desc',
                'default'  => 0,
            ],
            'option5' => [
                'type'     => 'bool',
                'required' => false,
                'advanced' => true,
                'evasion'  => false,
                'desc'     => 'option5 desc',
                'default'  => false,
            ],
        ];

        $handler = $this->clientMock([
            $this->createHttpResponsePack($this->options),
        ])->module;

        $this->module = new ModuleResource($this->info, $handler);
    }

    public function testModuleResourceConstructor()
    {
        $this->assertEquals($this->module->type, 'exploit');
        $this->assertEquals($this->module->options()->toArray(), $this->options);
        $this->assertEquals($this->module->option('option4'), $this->options['option4']);
        $this->assertEquals($this->module->advanced(), ['option1', 'option4', 'option5']);
        $this->assertEquals($this->module->evasion(), []);
        $this->assertEquals($this->module->required(), ['option2', 'option3']);
        $this->assertEquals($this->module->missingRequired(), ['option2']);
        $this->assertEquals(count($this->module->runOptions()), 4);

        $this->module->setOption('option5', true);

        $this->assertTrue($this->module->getOption('option5'));
    }

    public function testSetOptionFailsWhenOptionNotExist()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid option: option11');

        $this->module->setOption('option11');
    }

    public function testSetOptionFailsWhenTypeNotEqual()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Option type must be integer not string');

        $this->module->setOption('option4', 'test');
    }
}
