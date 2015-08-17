<?php
namespace Tev\Typo3Utils\Slots;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;

/**
 * Slot for entity change hooks.
 *
 * Extend this class, configuring the entity you want to listen to in the
 * constructor. Then, just provide the methods you want to hook into.
 *
 * The object manager is available at $this->om if you need it.
 *
 * Use this $entity->_isDirty('propertyName') method in your hooks to determine
 * whether or not a property has changed, if that affects the running of your
 * hook.
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
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $om;

    /**
     * Class name of entity to liste to.
     *
     * @var string
     */
    private $className;

    /**
     * Constructor.
     *
     * @param  string $className Class name of entity to listen to
     * @return void
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * After insert object hook.
     *
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $entity     Entity object
     * @param  string                                                $signalInfo Signal information
     * @return void
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
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $entity     Entity object
     * @param  string                                                $signalInfo Signal information
     * @return void
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
     * @param  \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $entity     Entity object
     * @param  string                                                $signalInfo Signal information
     * @return void
     */
    public function afterRemoveObject(DomainObjectInterface $entity, $signalInfo)
    {
        if ($entity instanceof $this->className) {
            if (method_exists($this, 'deleted')) {
                $this->deleted($entity);
            }
        }
    }
}
