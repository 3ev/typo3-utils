<?php
namespace Tev\Typo3Utils\Hook;

/**
 * Convenience class for registering entity hooks.
 *
 * Usage (in ext_tables.php):
 *
 *      \Tev\Typo3Utils\Hook\EntityRegistrar::register('Path\\To\\Hook\\Class');
 */
class EntityRegistrar
{
    /**
     * Register the given entity hook class.
     *
     * @param  string $className FQCN
     * @return void
     */
    public static function register($className)
    {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = $className;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = $className;
    }
}
