<?php

namespace Tev\Typo3Utils\Utility;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;

/**
 * A few utilities to dump out data.
 */
class Dump
{
    /**
     * @var  \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser
     */
    private $queryParser;

    /**
     * Constructor.
     *
     * @param  \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser  $queryParser
     * @return  void
     */
    public function __construct(Typo3DbQueryParser $queryParser)
    {
        $this->queryParser = $queryParser;
    }

    /**
     * Dump a query.
     *
     * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface  $query
     * @param  boolean  $return  Whether or not to return dumped query. Echos by default
     * @return  void
     */
    public function query(QueryInterface $query, $return = false)
    {
        DebuggerUtility::var_dump(
            $this->queryParser->parseQuery($query),
            null,
            8,
            false,
            true,
            $return
        );
    }
}
