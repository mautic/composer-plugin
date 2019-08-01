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
        $packageName = $this->getDirectoryName($package);

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
     * @param $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        $supportedTypes = [
            'mautic-plugin',
            'mautic-theme',
        ];

        return in_array($packageType, $supportedTypes);
    }

    /**
     * @param PackageInterface $package
     *
     * @return string
     */
    private function getDirectoryName(PackageInterface $package)
    {
        $extra = $package->getExtra();

        if (!empty($extra['install-directory-name'])) {
            return $extra['install-directory-name'];
        }

        return $this->toCamelCase($package->getPrettyName());
    }

    /**
     * @param string $packageName
     *
     * @return string
     */
    private function toCamelCase($packageName)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', basename($packageName))));
    }
}