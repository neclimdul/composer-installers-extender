<?php

declare(strict_types=1);

namespace NecLimDul\ComposerInstallerExtender\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Composer\Installer\InstallationManager;
use NecLimDul\ComposerInstallersExtender\Installers\Installer;
use NecLimDul\ComposerInstallersExtender\Plugin;

#[CoversClass(Plugin::class)]
class PluginTest extends TestCase
{
    protected Composer&MockObject $composer;

    protected IOInterface&MockObject $io;

    public function setUp(): void
    {
        parent::setUp();

        $this->composer = $this->createMock(Composer::class);
        $this->composer
            ->method('getConfig')
            ->willReturn(new Config());
        $this->composer
            ->method('getPackage')
            ->willReturn(new RootPackage('test', 'version', 'version'));

        $this->io = $this->createMock(IOInterface::class);
    }

    public function testActive(): void
    {
        $installationManager = $this->createMock(InstallationManager::class);
        $installationManager
            ->expects($this->once())
            ->method('addInstaller')
            ->with(new Installer($this->io, $this->composer));

        $this->composer
            ->expects($this->once())
            ->method('getInstallationManager')
            ->willReturn($installationManager);

        // There is no output to test from the activate method. Only test for
        // method call expectations.
        $plugin = new Plugin();
        $plugin->activate($this->composer, $this->io);
    }
}
