<?php

namespace Tev\Typo3Utils\Slots;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Slot for entity change hooks.
 *
 * Extend this class, configuring the entity you want to listen to in the
 * constructor. Then, just provide the methods you want to hook into.
 *
 * The object manager is available at $this->om if you need it.
 *
 * Use the $this->isDirty($entity, 'propertyName') method in your hooks to
 * determine whether or not a property has changed, if that affects the running
 * of your hook.
 *
 * Usage:
 *
 *     class MyEntitySlot extends EntitySlot
 *     {
 *         // Call parent constructor, configuring the entity to listen to
 *
 *         public function __construct()
 *         {
 *             parent::__construct('Path\To\My\Entity');
 *         }
 *
 *         // Post-created hook
 *
 *         public funtion created($entity) {}
 *
 *         // Post-updated hook
 *
 *         public function updated($entity) {}
 *
 *         // Post-deleted hook
 *
 *         public function deleted($entity) {}
 *     }
 */
abstract class EntitySlot implements SingletonInterface
{
    /**
     * Object manager.
     *
     * @var  \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $om;

    /**
     * Class name of entity to liste to.
     *
     * @var  string
     */
    private $className;

    /**
     * Dirty attribute cache.
     *
     * @var  array
     */
    private $dirty;

    /**
     * Constructor.
     *
     * @param  string  $className  Class name of entity to listen to
     * @return  void
     */
    public function __construct($className)
    {
        $this->className = $className;
        $this->dirty = [];
    }

    /**
     * After insert object hook.
     *
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface  $entity  Entity object
     * @param  string  $signalInfo  Signal information
     * @return  void
     */
    public function afterInsertObject(DomainObjectInterface $entity, $signalInfo)
    {
        if ($entity instanceof $this->className) {
            if (method_exists($this, 'created')) {
                $this->created($entity);
            }

            if (method_exists($this, 'saved')) {
                $this->saved($entity);
            }
        }
    }

    /**
     * After update object hook.
     *
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface  $entity  Entity object
     * @param  string  $signalInfo  Signal information
     * @return  void
     */
    public function afterUpdateObject(DomainObjectInterface $entity, $signalInfo)
    {
        if ($entity instanceof $this->className) {
            if (method_exists($this, 'updated')) {
                $this->updated($entity);
            }

            if (method_exists($this, 'saved')) {
                $this->saved($entity);
            }
        }
    }

    /**
     * After remove object hook.
     *
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface  $entity  Entity object
     * @param  string  $signalInfo  Signal information
     * @return  void
     */
    public function afterRemoveObject(DomainObjectInterface $entity, $signalInfo)
    {
        if ($entity instanceof $this->className) {
            if (method_exists($this, 'deleted')) {
                $this->deleted($entity);
            }
        }
    }

    /**
     * Check if the given attribute on the given model is dirty.
     *
     * @param  \TYPO3\CMS\Extbase\DomainObject\AbstractEntity  $entity  Entity
     * @param  string  $attr  Lower camel-cased attribute name
     * @return  boolean
     */
    protected function isDirty(AbstractEntity $entity, $attr)
    {
        $attr = (string) $attr;

        if ($entity->_isDirty($attr)) {
            $cls = str_replace('\\', '_', get_class($entity));
            $uid = (string) $entity->getUid();
            $val = $entity->{'get' . ucwords($attr)}();

            if (!isset($this->dirty[$cls])) {
                $this->dirty[$cls] = [];
            }

            if (!isset($this->dirty[$cls][$uid])) {
                $this->dirty[$cls][$uid] = [];
            }

            if (!isset($this->dirty[$cls][$uid][$attr])) {
                $this->dirty[$cls][$uid][$attr] = null;
            }

            if ($this->dirty[$cls][$uid][$attr] !== $val) {
                $this->dirty[$cls][$uid][$attr] = $val;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
