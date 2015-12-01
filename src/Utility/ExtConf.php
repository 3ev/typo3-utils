<?php
namespace Tev\Typo3Utils\Utility;

/**
 * Simple utility for easier access to ext conf variables.
 */
class ExtConf
{
    /**
     * The name of the extension being accessed.
     *
     * @var string
     */
    private $extName;

    /**
     * The loaded config array.
     *
     * @var array
     */
    private $conf;

    /**
     * Constructor.
     *
     * @param  string $extName The name of the extension being accessed.
     * @return void
     */
    public function __construct($extName)
    {
        $this->extName = $extName;
        $this->conf = [];

        $this->loadConfig();
    }

    /**
     * Get a config variable.
     *
     * @param  string     $key Config key
     * @return mixed|null      Variable, or null if not set
     */
    public function get($key)
    {
        if (isset($this->conf[$key])) {
            return (string) $this->conf[$key];
        }

        return null;
    }

    /**
     * Load ext conf.
     *
     * @return void
     */
    private function loadConfig()
    {
        $this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extName]);
    }
}
