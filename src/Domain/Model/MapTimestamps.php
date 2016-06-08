<?php

namespace Tev\Typo3Utils\Domain\Model;

/**
 * Model trait that provides getters and setters for the TYPO3 timestamp fields.
 *
 * If using this trait in a model, you'll also need to add crdate and tstamp
 * to the model's typoscript mapping setup.
 */
trait MapTimestamps
{
    /**
     * The time at which this entity was created. Unix timestamp.
     *
     * @var  int
     */
    protected $crdate;

    /**
     * The time at which this entity was last updated. Unix timestamp.
     *
     * @var  int
     */
    protected $tstamp;

    /**
     * Get the time at which this entity was created.
     *
     * @return  int  Unix timestamp
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set the time at which this entity was created.
     *
     * @param  int  $crdate  Unix timestamp
     * @return  void
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Get the time at which this entity was last updated.
     *
     * @return  int  Unix timestamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set the time at which this entity was last updated.
     *
     * @param  int  $tstamp  Unix timestamp
     * @return  void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }
}
