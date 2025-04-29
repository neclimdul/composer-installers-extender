<?php

declare(strict_types=1);

namespace NecLimDul\ComposerInstallersExtender\Installers;

use Composer\Installers\BaseInstaller;

/**
 * Provides a custom installer class for custom installer types.
 *
 * By default, the parent class has no specified locations. By not providing an
 * array of locations, we are forcing the installer to use custom installer
 * paths.
 */
class CustomInstaller extends BaseInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getLocations(?string $frameworkType = null): array
    {
        /* In some cases where installers use 'library' or other non namespaced
         * types composer will fail to handle the installer, but this project is
         * specifically supporting that case, so this works around composer.
         */
        return [ '' => '' ];
    }
}
