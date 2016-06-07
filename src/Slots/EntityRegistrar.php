<?php

namespace Tev\Typo3Utils\Slots;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Convenience class for registering entity slots.
 *
 * Usage (in ext_localconf.php):
 *
 *     \Tev\Typo3Utils\Slots\EntityRegistrar::register('Path\\To\\Slot\\Class');
 */
class EntityRegistrar
{
    /**
     * Register the given entity slot class.
     *
     * @param  string  $className  FQCN
     * @return  void
     */
    public static function register($className)
    {
        $dispatcher = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');

        $dispatcher->connect(
            'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend',
            'afterInsertObject',
            $className,
            'afterInsertObject'
        );

        $dispatcher->connect(
            'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend',
            'afterUpdateObject',
            $className,
            'afterUpdateObject'
        );

        $dispatcher->connect(
            'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend',
            'afterRemoveObject',
            $className,
            'afterRemoveObject'
        );
    }
}
