<?php

declare(strict_types=1);

namespace NecLimDul\ComposerInstallerExtender\Tests\Installers;

use NecLimDul\ComposerInstallersExtender\Installers\CustomInstaller;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CustomInstaller::class)]
class CustomInstallerTest extends TestCase
{
    public function testLocations(): void
    {
        $installer = (new \ReflectionClass(
            CustomInstaller::class
        ))->newInstanceWithoutConstructor();
        $this->assertSame(
            ['' => ''],
            $installer->getLocations()
        );
    }
}
