<?php

namespace Mautic\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class InstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    const MAUTIC_PLUGINS_FILE = __DIR__.'/../../../../plugins.json';

    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'postPackageInstall',
            PackageEvents::POST_PACKAGE_UNINSTALL => 'postPackageUninstall',
        ];
    }

    public function postPackageInstall(PackageEvent $event)
    {
        if (!$this->shouldProcess($event)) {
            return;
        }

        $plugins = $this->getPluginsArray();
        /** @var \Composer\Package\Package $package */
        $package = $event->getOperation()->getPackage();

        $plugins[$package->getName()] = $package->getVersion();

        $this->writePluginsFile($plugins);
    }

    public function postPackageUninstall(PackageEvent $event)
    {
        if (!$this->shouldProcess($event)) {
            return;
        }

        $plugins = $this->getPluginsArray();
        /** @var \Composer\Package\Package $package */
        $package = $event->getOperation()->getPackage();

        if (array_key_exists($package->getName(), $plugins)) {
            unset($plugins[$package->getName()]);

            $this->writePluginsFile($plugins);
        }
    }

    private function shouldProcess(PackageEvent $event)
    {
        if ($event->isPropagationStopped()) {
            return false;
        }

        /** @var \Composer\Package\Package $package */
        $package = $event->getOperation()->getPackage();
        $type = $package->getType();

        if ($type !== 'mautic-plugin' && $type !== 'mautic-theme') {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function getPluginsArray()
    {
        if (file_exists(self::MAUTIC_PLUGINS_FILE)) {
            $pluginsJson = file_get_contents(self::MAUTIC_PLUGINS_FILE);

            return json_decode($pluginsJson, true) ?: [];
        }

        return [];
    }

    private function writePluginsFile(array $data)
    {
        file_put_contents(self::MAUTIC_PLUGINS_FILE, json_encode($data, JSON_PRETTY_PRINT));
    }
}