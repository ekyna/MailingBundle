<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\CoreBundle\Model\TimestampableInterface;
use Ekyna\Bundle\CoreBundle\Model\TimestampableTrait;
use Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface;
use Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectTrait;

/**
 * Class Execution
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Execution implements RecipientsSubjectInterface, TimestampableInterface
{
    use RecipientsSubjectTrait,
        TimestampableTrait;


    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var Campaign
     */
    protected $campaign;

    /**
     * @var ArrayCollection|RecipientList[]
     */
    protected $recipientLists;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * @var \DateTime
     */
    protected $startedAt;

    /**
     * @var \DateTime
     */
    protected $completedAt;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var int
     */
    protected $failed = 0;

    /**
     * @var int
     */
    protected $sent = 0;

    /**
     * @var int
     */
    protected $opened = 0;

    /**
     * @var int
     */
    protected $visited = 0;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->recipientLists = new ArrayCollection();
        $this->recipients = new ArrayCollection();
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return Execution
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Execution
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Execution
     */
    public function setStartDate(\DateTime $startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Returns the campaign.
     *
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Sets the campaign.
     *
     * @param Campaign $mailing
     * @return Execution
     */
    public function setCampaign(Campaign $mailing)
    {
        $this->campaign = $mailing;
        return $this;
    }

    /**
     * Returns the recipient lists.
     *
     * @return ArrayCollection|RecipientList[]
     */
    public function getRecipientLists()
    {
        return $this->recipientLists;
    }

    /**
     * Returns whether the execution has the given recipient list or not.
     *
     * @param RecipientList $recipientList
     * @return bool
     */
    public function hasRecipientList(RecipientList $recipientList)
    {
        return $this->recipientLists->contains($recipientList);
    }

    /**
     * Adds the recipient list.
     *
     * @param RecipientList $recipientList
     * @return Execution
     */
    public function addRecipientList(RecipientList $recipientList)
    {
        if (!$this->hasRecipientList($recipientList)) {
            $this->recipientLists->add($recipientList);
        }
        return $this;
    }

    /**
     * Removes the the recipient list.
     *
     * @param RecipientList $recipientList
     * @return Execution
     */
    public function removeRecipientList(RecipientList $recipientList)
    {
        if ($this->hasRecipientList($recipientList)) {
            $this->recipientLists->removeElement($recipientList);
        }
        return $this;
    }

    /**
     * Sets the recipient lists.
     *
     * @param ArrayCollection $recipientLists
     * @return Execution
     */
    public function setRecipientLists(ArrayCollection $recipientLists)
    {
        $this->recipientLists = $recipientLists;
        return $this;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Execution
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Returns the locked.
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Sets the locked.
     *
     * @param boolean $locked
     * @return Execution
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * Returns the startedAt.
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Sets the startedAt.
     *
     * @param \DateTime $startedAt
     * @return Execution
     */
    public function setStartedAt(\DateTime $startedAt = null)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * Returns the completedAt.
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Sets the completedAt.
     *
     * @param \DateTime $completedAt
     * @return Execution
     */
    public function setCompletedAt(\DateTime $completedAt = null)
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    /**
     * Returns the total count.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Increments the total count.
     *
     * @return Execution
     */
    public function incrementTotal()
    {
        $this->total++;
        return $this;
    }

    /**
     * Sets the total count.
     *
     * @param int $total
     * @return Execution
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Returns the failed count.
     *
     * @return int
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * Increments the failed count.
     *
     * @return Execution
     */
    public function incrementFailed()
    {
        $this->failed++;
        return $this;
    }

    /**
     * Sets the failed count.
     *
     * @param int $failed
     * @return Execution
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;
        return $this;
    }

    /**
     * Returns the sent count.
     *
     * @return int
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Increments the sent count.
     *
     * @return Execution
     */
    public function incrementSent()
    {
        $this->sent++;
        return $this;
    }

    /**
     * Sets the sent count.
     *
     * @param int $sent
     * @return Execution
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
        return $this;
    }

    /**
     * Returns the opened count.
     *
     * @return int
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Increments the opened count.
     *
     * @return Execution
     */
    public function incrementOpened()
    {
        $this->opened++;
        return $this;
    }

    /**
     * Sets the opened count.
     *
     * @param int $opened
     * @return Execution
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;
        return $this;
    }

    /**
     * Returns the visited count.
     *
     * @return int
     */
    public function getVisited()
    {
        return $this->visited;
    }

    /**
     * Increments the visited count.
     *
     * @return Execution
     */
    public function incrementVisited()
    {
        $this->visited++;
        return $this;
    }

    /**
     * Sets the visited count.
     *
     * @param int $visited
     * @return Execution
     */
    public function setVisited($visited)
    {
        $this->visited = $visited;
        return $this;
    }
}
