# Composer Plugin for Mautic

This plugin will allow Mautic users that manage their instance via composer to easily install your plugins and themes.
Simply host your plugin on GitHub, add it to packagist, and you're ready to go.

There are two requirements to using this plugin.

1) After adding a `composer.json` to the root of your plugin repository, run `composer require mautic/composer-plugin`.
2) Set the `type` in your `composer.json` file to either `mautic-plugin` or `mautic-theme`, depending on what
your code is.

Your `composer.json` should now look something like this:

```json
{
  "name": "dongilbert/my-twig-extension-bundle",
  "type": "mautic-plugin",
  "require": {
    "mautic/composer-plugin": "*"
  }
}
```