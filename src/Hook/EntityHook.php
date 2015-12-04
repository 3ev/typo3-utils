<?php
namespace Tev\Typo3Utils\Hook;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class that simplifies the BE hook process for entities.
 *
 * Simply extend this class in your own hooks. Each class deals with a specific
 * table.
 *
 * The Extbase object manager is available at $this->om should you need it.
 *
 * Usage:
 *
 *     class MyEntityHook extends EntityHook
 *     {
 *         // Call parent constructor and pass in table name you want to listen
 *         // for
 *
 *         public function __construct()
 *         {
 *             parent::__construct('table_name');
 *         }
 *
 *         // Fired before an object is created. Return an array of field data
 *         // if you want to modify what's saved
 *
 *         protected function creating($fields) {}
 *
 *         // Fired after an object is created
 *
 *         protected function created($uid, $fields) {}
 *
 *         // Fired before an object is updated. Return an array of field data
 *         // if you want to modify what's saved
 *
 *         protected function updating($uid, $dirty) {}
 *
 *         // Fired after an object is updated
 *
 *         protected function updated($uid, $dirty) {}
 *
 *         // Fired before an object is updated or created. Return an array of
 *         // field data if you want to modify what's saved
 *
 *         protected function saving($uid, $dirty) {}
 *
 *         // Fired after an object is updated or created
 *
 *         protected function saved($uid, $dirty) {}
 *
 *         // Fired before an object is delete. Return false if cancel the
 *         // delete operation
 *
 *         protected function deleting($uid, $fields) {}
 *     }
 */
abstract class EntityHook
{
    /**
     * Object manager.
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $om;

    /**
     * Name of table to listen to.
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor.
     *
     * @param  string $table Name of table to listen to
     * @return void
     */
    public function __construct($table)
    {
        $this->om = GeneralUtility::makeInstance(ObjectManager::class);
        $this->table = $table;
    }

    /**
     * TYPO3 pre-save hook.
     *
     * @param  string                                   $status 'new' for new entities, 'update' for existing entities
     * @param  string                                   $table  Database table name being saved
     * @param  string|int                               $id     UID or New ID of entity being saved
     * @param  array                                    $fields Array of fields being updated
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler $dh     Class that triggered hook
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fields, $dh)
    {
        if ($table === $this->table) {
            if ($status === 'new') {
                $res = $this->creating($fields);

                if (is_array($res)) {
                    $fields = $res;
                }
            } else {
                $res = $this->updating($id, $fields);

                if (is_array($res)) {
                    $fields = $res;
                }
            }

            $res = $this->saving($status === 'new' ? null : $uid, $fields);

            if (is_array($res)) {
                $fields = $res;
            }
        }
    }

    /**
     * TYPO3 post-save hook.
     *
     * @param  string                                   $status 'new' for new entities, 'update' for existing entities
     * @param  string                                   $table  Database table name being saved
     * @param  string|int                               $id     UID or New ID of entity being saved
     * @param  array                                    $fields Array of fields being updated
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler $dh     Class that triggered hook
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fields, $dh)
    {
        if ($table === $this->table) {
            $uid = $this->getUid($status, $id, $dh);

            if ($status === 'new') {
                $this->created($uid, $fields);
            } else {
                $this->updated($uid, $fields);
            }

            $this->saved($uid, $fields);
        }
    }

    /**
     * TYPO3 pre-delete hook.
     *
     * @param  string                                   $table  Table name
     * @param  int                                      $id     UID of entity being delete
     * @param  array                                    $fields Array of record fields
     * @param  boolean                                  $cancel Whether or not to cancel deletion
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler $dh     Class that triggered hook
     * @return void
     */
    public function processCmdmap_deleteAction($table, $id, $fields, &$cancel, $dh)
    {
        if ($table === $this->table) {
            if (($res = $this->deleting($id, $fields)) !== null) {
                $cancel = !$res;
            }
        }
    }

    /**
     * Pre-create hook.
     *
     * @param  array      $fields Field data of record being created
     * @return null|array         Returns modified field data, or null
     */
    protected function creating($fields) {}

    /**
     * Post-create hook.
     *
     * @param  int   $uid    New record UID
     * @param  array $fields Field data of record that was created
     * @return void
     */
    protected function created($uid, $fields) {}

    /**
     * Pre-update hook.
     *
     * @param  int        $uid   Record UID
     * @param  array      $dirty Modified field data of record being updated
     * @return null|array        Returns modified field data, or null
     */
    protected function updating($uid, $dirty) {}

    /**
     * Post-update hook.
     *
     * @param  int   $uid   Record UID
     * @param  array $dirty Modified field data of record that was updated
     * @return void
     */
    protected function updated($uid, $dirty) {}

    /**
     * Pre-create or update hook.
     *
     * @param  int|null   $uid   Record UID. Will be null if record is new
     * @param  array      $dirty (Modified) field data or record being created or updated
     * @return null|array        Returns modified field data, or null
     */
    protected function saving($uid, $dirty) {}

    /**
     * Post-create or update hook.
     *
     * @param  int   $uid   Record UID
     * @param  array $dirty Modifed field data of record that was created or updated
     * @return void
     */
    protected function saved($uid, $dirty) {}

    /**
     * Pre-delete hook.
     *
     * @param  int          $uid    Record UID
     * @param  array        $fields Record fields
     * @return null|boolean         Null, or false to cancel delete or true to continue
     */
    protected function deleting($uid, $fields) {}

    /**
     * Get the correct entity UID even if a record is new.
     *
     * @param  string                                   $status
     * @param  string|int                               $id
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler $dh
     * @return int
     */
    private function getUid($status, $id, $dh)
    {
        if ($status === 'new') {
            return $dh->substNEWwithIDs[$id];
        } else {
            return $id;
        }
    }
}
