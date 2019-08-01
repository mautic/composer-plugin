# Composer Plugin for Mautic

This plugin will allow Mautic users that manage their instance via composer to easily install your plugins and themes.
Simply host your plugin on GitHub, add it to packagist, and you're ready to go.

There are two requirements to using this plugin.

1) After adding a `composer.json` to the root of your plugin repository, run `composer require mautic/composer-plugin`.
2) Set the `type` in your `composer.json` file to either `mautic-plugin` or `mautic-theme`, depending on what
your code is.
3) Optionally, set the `install-directory-name` under `extra` to define the directory the plugin will be installed into. This should match your plugin's namespace. This will default to a camel case version of `name` if not defined.
Your `composer.json` should now look something like this:

```json
{
  "name": "dongilbert/my-twig-extension-bundle",
  "type": "mautic-plugin",
  "extra": {
    "install-directory-name": "MyTwigExtensionBundle"  
  },
  "require": {
    "mautic/composer-plugin": "*"
  }
}
```