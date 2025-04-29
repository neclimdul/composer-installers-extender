<?php

declare(strict_types=1);

namespace NecLimDul\ComposerInstallersExtender\Installers;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Installers\Installer as InstallerBase;

class Installer extends InstallerBase
{
    /**
     * A list of installer types.
     *
     * @var string[]
     */
    protected array $installerTypes;

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package): string
    {
        /** @phpstan-ignore argument.type */
        $installer = new CustomInstaller($package, $this->composer, $this->io);
        $path = $installer->getInstallPath($package, $package->getType());

        return $path ?: LibraryInstaller::getInstallPath($package);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType): bool
    {
        return in_array($packageType, $this->getInstallerTypes());
    }

    /**
     * Get a list of custom installer types.
     *
     * @return string[]
     */
    public function getInstallerTypes(): array
    {
        if (!isset($this->installerTypes)) {
            $extra = $this->composer->getPackage()->getExtra();
            $this->installerTypes = $extra['installer-types'] ?? [];
        }

        return $this->installerTypes;
    }
}
