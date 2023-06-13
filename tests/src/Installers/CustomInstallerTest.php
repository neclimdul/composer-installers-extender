<?php

declare(strict_types = 1);

namespace OomphInc\ComposerInstallersExtender\Tests\Installers;

use PHPUnit\Framework\TestCase;
use OomphInc\ComposerInstallersExtender\Installers\CustomInstaller;

class CustomInstallerTest extends TestCase
{
    public function testLocations(): void
    {
        $installer = (new \ReflectionClass(CustomInstaller::class))
            ->newInstanceWithoutConstructor();
        $this->assertSame(
            [ 0 => false, '' => false],
            $installer->getLocations()
        );
    }
}
