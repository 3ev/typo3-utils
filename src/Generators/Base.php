<?php

namespace Tev\Typo3Utils\Generators;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * Base generator for creating records via TYPO3's DataHandler API.
 */
abstract class Base implements SingletonInterface
{
    /**
     * @var  \TYPO3\CMS\Core\DataHandling\DataHandler
     */
    protected $dh;

    /**
     * Constructor.
     *
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler  $dh
     * @return  void
     */
    public function __construct(DataHandler $dh)
    {
        $this->dh = $dh;
    }

    /**
     * Get the name of the table records should be created for.
     *
     * @return  string
     */
    abstract protected function getTable();

    /**
     * Create a new record under the given PID.
     *
     * @param  int  $pid
     * @param  array  $attrs  Set of attributes to add to the record
     * @return  int|null  UID of created record, or null if creation failed
     */
    public function create($pid, $attrs = [])
    {
        $table = $this->getTable();

        $newUid = 'NEW_' . uniqid($table);

        $this->dh->stripslashes_values = 0;
        $this->dh->start([
            $table => [
                $newUid => array_merge($attrs, ['pid' => $pid])
            ]
        ], null);
        $this->dh->process_datamap();

        if (isset($this->dh->substNEWwithIDs[$newUid])) {
            return $this->dh->substNEWwithIDs[$newUid];
        } else {
            return null;
        }
    }
}
