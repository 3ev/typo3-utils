<?php
namespace Tev\Typo3Utils\Plugin;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Helper class to make it easier to setup wizicons for plugins.
 *
 * Usage:
 *
 *     class MyIcon extends \Tev\Typo3Utils\Plugin\WizIcon
 *     {
 *         public function __construct()
 *         {
 *             parent::__construct(
 *                 'my_ext',
 *                 'myplugin',
 *                 'ext_icon.png' // Optional
 *                 'locallang.xlf' // Optional
 *             );
 *         }
 *     }
 *
 * Your configured language file needs to contain the following entries:
 *
 *     - my_ext.plugin.myplugin.title
 *     - my_ext.plugin.myplugin.description
 */
abstract class WizIcon
{
    /**
     * The extension name.
     *
     * @var string
     */
    private $extension;

    /**
     * The plugin name.
     *
     * @var string
     */
    private $plugin;

    /**
     * Icon file name.
     *
     * @var string
     */
    private $iconFile;

    /**
     * Language file name.
     *
     * @var string
     */
    private $langFile;

    /**
     * Language array cache.
     *
     * @var array
     */
    private $ll;

    /**
     * Constructor.
     *
     * @param  string $extension Extension key
     * @param  string $plugin    Plugin name
     * @param  string $iconFile  Icon file name. Defaults to ext_icon.png
     * @param  string $langFile  Lang file name. Defaults to locallang.xlf
     * @return void
     */
    public function __construct(
        $extension,
        $plugin,
        $iconFile = 'ext_icon.png',
        $langFile = 'locallang.xlf'
    ) {
        $this->extension = $extension;
        $this->plugin = $plugin;
        $this->iconFile = $iconFile;
        $this->langFile = $langFile;
        $this->ll = null;
    }

    /**
     * Add the icon to the wizard items array.
     *
     * @param  array $wizardItems The current wizard items
     * @return array
     */
    public function proc($wizardItems)
    {
        $wizardItems[$this->getWizardKey()] = [
            'icon' => $this->getExtRelPath() . $this->iconFile,
            'title' => $this->translate('title'),
            'description' => $this->translate('description'),
            'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . $this->getListType()
        ];

        return $wizardItems;
    }

    /**
     * Retrieve the given label from the language file.
     *
     * Labels shoudl be named liked:
     *
     * {extension}.plugin.{plugin}.{label}
     *
     * @param  string $key Label key
     * @return string      Translate value
     */
    private function translate($key)
    {
        return $GLOBALS['LANG']->getLLL(
            "{$this->extension}.plugin.{$this->plugin}.{$key}",
            $this->getLL()
        );
    }

    /**
     * Load the the plugin language file, and return its contents.
     *
     * Loads the file:
     *
     * Resources/Private/Language/locallang.xlf
     *
     * @return array
     */
    private function getLL()
    {
        if ($this->ll === null) {
            $this->ll = $GLOBALS['LANG']->includeLLFile(
                $this->getExtPath() . 'Resources/Private/Language/' . $this->langFile,
                false
            );
        }

        return $this->ll;
    }

    /**
     * Get the ext path for this extension.
     *
     * @return string
     */
    private function getExtPath()
    {
        return ExtensionManagementUtility::extPath($this->extension);
    }

    /**
     * Get the ext rel path for this extension.
     *
     * @return string
     */
    private function getExtRelPath()
    {
        return ExtensionManagementUtility::extRelPath($this->extension);
    }

    /**
     * Get the wizard items entry key for this icon.
     *
     * @return string
     */
    private function getWizardKey()
    {
        $ext = $this->getExtNameNoUnderscores();

        return "plugins_tx_{$ext}_{$this->plugin}";
    }

    /**
     * Get the list type for the configured plugin.
     *
     * @return string
     */
    private function getListType()
    {
        $ext = $this->getExtNameNoUnderscores();

        return "{$ext}_{$this->plugin}";
    }

    /**
     * Get the configured extension name without any underscores.
     *
     * @return string
     */
    private function getExtNameNoUnderscores()
    {
        return str_replace('_', '', $this->extension);
    }
}
