<?php

namespace Mautic\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $packageType = $package->getType();
        $packageName = $this->toCamelCase($package->getPrettyName());

        switch ($packageType) {
            case 'mautic-theme':
                return 'themes/'.$packageName;
            case 'mautic-plugin':
                return 'plugins/'.$packageName;
            default:
                throw new \InvalidArgumentException(
                    'Unable to install. '
                );

        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $supportedTypes = [
            'mautic-plugin',
            'mautic-theme',
        ];

        return in_array($packageType, $supportedTypes);
    }

    private function toCamelCase($packageName)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', basename($packageName))));
    }
}