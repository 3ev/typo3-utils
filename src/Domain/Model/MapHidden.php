<?php

namespace Tev\Typo3Utils\Domain\Model;

/**
 * Model trait that provides getters and setters for the TYPO3 'hidden' field.
 *
 * If using this trait in a model, you'll also need to add hidden to the model's
 * typoscript mapping setup.
 */
trait MapHidden
{
    /**
     * Whether or not this entity is hidden.
     *
     * @var  boolean
     */
    protected $hidden;

    /**
     * Get whether or not this entity is hidden.
     *
     * @return  boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set whether or not this entity is hidden.
     *
     * @param  booelan  $hidden
     * @return  void
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }
}
