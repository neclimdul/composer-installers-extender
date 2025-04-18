<?php

declare(strict_types = 1);

namespace OomphInc\ComposerInstallersExtender\Installers;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use PHPUnit\Framework\TestCase;
use Composer\Package\Package;

class InstallerTest extends TestCase
{
    protected $composer;

    protected $io;

    public function setUp(): void
    {
        parent::setUp();

        $this->composer = $this->createMock(Composer::class);
        $this->composer
            ->method('getConfig')
            ->willReturn(new class extends Config
            {
            });

        $this->io = $this->createMock(IOInterface::class);
    }

    public function testGetInstallPath(): void
    {
        $this->composer
            ->method('getPackage')
            ->willReturn($this->mockRootPackage([
                'installer-types' => ['custom-type'],
                'installer-paths' => [
                    'custom/path/{$name}' => ['type:custom-type'],
                ],
            ]));

        $installer = new Installer($this->io, $this->composer);

        $package = new Package('oomphinc/test', '1.0.0', '1.0.0');
        $package->setType('custom-type');

        $this->assertEquals(
            'custom/path/test',
            $installer->getInstallPath($package)
        );
    }

    private function mockRootPackage($values)
    {
        $package = new RootPackage('test', 'version', 'version');
        $package->setExtra($values);
        return $package;
    }

    public function testSupports(): void
    {
        $installer = new class extends Installer {
            public function __construct() {}

            public function getInstallerTypes(): array
            {
                return ['custom-type'];
            }
        };

        $this->assertTrue($installer->supports('custom-type'));
        $this->assertFalse($installer->supports('oomph'));
    }

    /**
     * @dataProvider installerTypesDataProvider
     */
    public function testGetInstallerTypes($package, array $expected): void
    {
        $this->composer
            ->method('getPackage')
            ->willReturn($package);

        $installer = new Installer($this->io, $this->composer);
        $this->assertEquals($expected, $installer->getInstallerTypes());
    }

    public function installerTypesDataProvider(): array
    {
        return [
            [
                $this->mockRootPackage([
                    'installer-types' => ['custom-type'],
                    'installer-paths' => [
                        'custom/path/{$name}' => ['type:custom-type'],
                    ],
                ]),
                ['custom-type'],
            ],
            [
                $this->mockRootPackage([]),
                [],
            ],
        ];
    }
}
