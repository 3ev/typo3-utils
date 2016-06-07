<?php

namespace Tev\Typo3Utils\Utility;

use Exception;

/**
 * Numerous page utility methods.
 */
class Page
{
    /**
     * Convenience method for fetching the full URI to a page.
     *
     * Uses TSFE and Typolink, so TSFE must be available. If no TSFE is available,
     * this method will attempt to bootstrap it if $forceTsfe is true.
     *
     * @param  int  $pageUid  Page UID
     * @param  boolean  $forceTsfe  Attempt to bootstrap TSFE if unavailable. false by default
     * @return  string
     * @throws  \Exception  If no TSFE and did not bootstrap
     */
    public function getUri($pageUid, $forceTsfe = false)
    {
        if (!isset($GLOBALS['TSFE'])) {
            if ($forceTsfe) {
                (new Tsfe)->create($this->getRootPage($pageUid));
            } else {
                throw new Exception('TSFE must be available to use this method');
            }
        }

        return $GLOBALS['TSFE']->cObj->typoLink_URL([
            'parameter' => $pageUid,
            'forceAbsoluteUrl' => true,
            'linkAccessRestrictedPages' => 1,
            'useCacheHash' => true
        ]);
    }

    /**
     * Get the root page UID of the given page.
     *
     * @param  int  $pageUid  Page UID to get root page for
     * @return  int  Root page UID
     */
    public function getRootPage($pageUid)
    {
        $info = $this->getPageInfo($pageUid);

        while ($info && $info['pid'] && !$info['is_siteroot']) {
            $info = $this->getPageInfo((int) $info['pid']);
        }

        return (int) $info['uid'];
    }

    /**
     * Get the following information about the given page.
     *
     * - uid
     * - pid
     * - tstamp
     * - crdate
     * - hidden
     * - title
     * - subtitle
     * - doktype
     * - nav_hide
     * - storage_pid
     * - is_site_root
     *
     * @param  int  $pageUid  Page UID
     * @return  array|false  Page info array, or false if not found
     */
    public function getPageInfo($pageUid)
    {
        return $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
            'uid, pid, tstamp, crdate, hidden, title, subtitle, doktype, nav_hide, storage_pid, is_siteroot',
            'pages',
            'uid = ' . $pageUid
        );
    }
}
