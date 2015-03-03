<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectInterface;
use Ekyna\Bundle\MailingBundle\Model\RecipientsSubjectTrait;

/**
 * Class RecipientList
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientList implements RecipientsSubjectInterface
{
    use RecipientsSubjectTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->recipients = new ArrayCollection();
    }

    /**
     * Returns the string representation.
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
     * Set name
     *
     * @param string $name
     * @return RecipientList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
