#TYPO3 Utils

[![Latest Stable Version](https://poser.pugx.org/3ev/typo3-utils/version)](https://packagist.org/packages/3ev/typo3-utils) [![License](https://poser.pugx.org/3ev/typo3-utils/license)](https://packagist.org/packages/3ev/typo3-utils)

> Utility classes to provide simpler APIs to common TYPO3 tasks.

##Installation

You can include this library in any of your TYPO3 extensions via Composer:

```
$ composer require "3ev/typo3-utils"
```

##Usage

TYPO3 Utils provides the following utility classes to help ease building TYPO3
extensions:

####Tev\Typo3Utils\Domain\Model

This namespace provides a few useful traits for re-usable model functionality,
such as adding getters and setters for `crdate`, `tstamp` and `hidden`.

####Tev\Typo3Utils\Generators

This namespace contains 'generators', which are wrappers around TYPO3's
[DataHandler](https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Typo3CoreEngine/Database/Index.html) API.

Generators can be used to create page, content and template and records from the
backend or CLI scripts (a `BE_USER` is required).

####Tev\Typo3Utils\Hook\EntityHook & Tev\Typo3Utils\Hook\EntityRegistrar

The `EntityHook` and `EntityRegistrar` classes make it easy to listen to backend
TYPO3 lifecycle events on database entities.

Just extend `EntityHook`, and specify the table you want to listen to in the
parent constructor. You'll then be able to implement `creating`, `created`, `updating`,
`updated`, `saving`, `saved` and `deleted` methods as you wish, which will be called
when the relevant action happens from the TYPO3 backend.

You can register your hook class by simply adding:

```php
\Tev\Typo3Utils\Hook\EntityRegistrar::register('Path\\To\\Hook\\Class');
```

to your extension's `ext_tables.php` file. This saves you writing a complex
TYPO3 hook definition.

####Tev\Typo3Utils\Log\Writer\FileWriter

The [default TYPO3 log file writer](https://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Logging/Writers/Index.html#filewriter) doesn't let you write to log files outside of the publicly served directory.

This can be insecure, so this simple writer class allows you to write log files
anywhere on the filesystem.

####Tev\Typo3Utils\Plugin\WizIcon

This class makes it trivial to register wizicons for plugins in your extension.

Just extend the base wizicon class and configure the icon details in the parent
constructor:

```php
namespace My\Extension;

class MyIcon extends \Tev\Typo3Utils\Plugin\WizIcon
{
    public function __construct()
    {
        parent::__construct(
            // Your extension's name, with underscores

            'my_ext',

            // The plugin name(s) you'd like the wizicon to be used for

            ['myplugin', 'myotherplugin],

            // Optional. The icon file name

            'ext_icon.png'

            // Optional. The language file you'd like to use

            'locallang.xlf'
        );
    }
}
```

then, just register the icon class as normal in `ext_tables.php`:

```php
if (TYPO3_MODE === 'BE') {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['My\\Extension\\WizIcon'] =
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/WizIcon.php';
}
```

####Tev\Typo3Utils\Services\GeocodingService

This class provides an Extbase-compatible wrapper around the [Geocoder PHP](http://geocoder-php.org/Geocoder)
library.

####Tev\Typo3Utils\TCA\Label

This utility class provides a TCA userfunc to allow you to set labels consisting
of multiple fields, with a custom separator.

For example:

```php
'crtl' => [
    'label_userFunc' => 'Tev\\Typo3Utils\\TCA\\Label->run',
    'label_userFunc_options' => [
        // Required, single field name of array of field names

        'fields' => [
            'first_name',
            'last_name'
        ],

        // Optional, defaults to ' '

        'glue' => ', '
     ]
]
```

####Tev\Typo3Utils\Utility\ExtConf

This class provides a simple API for accessing extconf variables:

```php
$conf = new \Tev\Typo3Utils\Utility\ExtConf('my_ext');
$conf->get('config_key');
```

####Tev\Typo3Utils\Utility\Page

This class provides a few methods for retrieving data on TYPO3 pages.

See [the class](https://github.com/3ev/typo3-utils/blob/master/src/Utility/Page.php) for
more information.

####Tev\Typo3Utils\Utility\Tsfe

This class provides a simple API for initialising the `TSFE` on the CLI or TYPO3
backend. You just need to set the root page ID an optionally provide a host name:

```php
$tsfe = \Tev\Typo3Utils\Utility\Tsfe;
$tsfe->create(1 /*, www.hostname.com */);
```

##License

MIT © 3ev
