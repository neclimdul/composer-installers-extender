<?php

declare(strict_types=1);

namespace NecLimDul\ComposerInstallerExtender\Tests\Installers;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use NecLimDul\ComposerInstallersExtender\Installers\Installer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Composer\Package\Package;

#[CoversClass(Installer::class)]
class InstallerTest extends TestCase
{
    protected Composer&MockObject $composer;

    protected IOInterface&MockObject $io;

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
            ->willReturn(self::mockRootPackage([
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

    /**
     * @param mixed[] $values
     */
    private static function mockRootPackage(array $values): RootPackage
    {
        $package = new RootPackage('test', 'version', 'version');
        $package->setExtra($values);
        return $package;
    }

    public function testSupports(): void
    {
        $installer = new class extends Installer {
            public function __construct()
            {
            }

            public function getInstallerTypes(): array
            {
                return ['custom-type'];
            }
        };

        $this->assertTrue($installer->supports('custom-type'));
        $this->assertFalse($installer->supports('oomph'));
    }

    /**
     * @param \Composer\Package\RootPackage $package
     * @param string[] $expected
     */
    #[DataProvider('installerTypesDataProvider')]
    public function testGetInstallerTypes(RootPackage $package, array $expected): void
    {
        $this->composer
            ->method('getPackage')
            ->willReturn($package);

        $installer = new Installer($this->io, $this->composer);
        $this->assertEquals($expected, $installer->getInstallerTypes());
    }

    /**
     * @return array{\Composer\Package\RootPackage,string[]}[]
     */
    public static function installerTypesDataProvider(): array
    {
        return [
            [
                self::mockRootPackage([
                    'installer-types' => ['custom-type'],
                    'installer-paths' => [
                        'custom/path/{$name}' => ['type:custom-type'],
                    ],
                ]),
                ['custom-type'],
            ],
            [
                self::mockRootPackage([]),
                [],
            ],
        ];
    }
}
