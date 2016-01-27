<?php
namespace Tev\Typo3Utils\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility class to bootstrap the TSFE if it's unavailable.
 */
class Tsfe
{
    /**
     * Create the TSFE.
     *
     * @param  int     $rootPageId Root page UID to bootstrap TSFE from
     * @param  boolean $setHost    Optionally set the HTTP_HOST. Useful if on CLI. false by default
     * @return void
     */
    public function create($rootPageId, $setHost = false)
    {
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker');
            $GLOBALS['TT']->start();
        }

        $tsfe = GeneralUtility::makeInstance(
            'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',
            $GLOBALS['TYPO3_CONF_VARS'],
            $rootPageId,
        0);

        $tsfe->initFEuser();
        $tsfe->determineId();
        $tsfe->getPageAndRootline();
        $tsfe->initTemplate();
        $tsfe->getConfigArray();
        $tsfe->newCObj();

        $GLOBALS['TSFE'] = $tsfe;

        if ($setHost) {
            $domains = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
                'domainName',
                'sys_domain',
                'hidden = 0 AND pid = "' . $rootPageId . '"'
            );

            if (count($domains)) {
                $_SERVER['HTTP_HOST'] = $domains[0]['domainName'];
            }
        }
    }
}
