<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Bundle\UserBundle\Model\UserInterface;

/**
 * Class Recipient
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Recipient
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName; // TODO remove (use only  full name)

    /**
     * @var string
     */
    protected $lastName; // TODO remove (use only  full name)

    /**
     * @var string (non persisted)
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $recipientLists;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->recipientLists = new ArrayCollection();
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        $name = trim($this->firstName . ' ' . $this->lastName);
        if (0 < strlen($name)) {
            return sprintf('%s <%s>', $this->email, $name);
        }
        return $this->email;
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
     * Set user
     *
     * @param UserInterface $user
     * @return Recipient
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Recipient
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Recipient
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        $this->name = null;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Recipient
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        $this->name = null;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns the full name.
     *
     * @return null|string
     */
    public function getName()
    {
        if (null === $this->name) {
            if (0 < strlen($this->firstName) && 0 < strlen($this->lastName)) {
                $this->name = $this->firstName . ' ' . $this->lastName;
            } else {
                $this->name = '';
            }
        }
        return $this->name;
    }

    /**
     * Returns the recipientLists.
     *
     * @return ArrayCollection
     */
    public function getRecipientLists()
    {
        return $this->recipientLists;
    }

    /**
     * Returns whether the recipient has the given list or not.
     *
     * @param RecipientList $recipientList
     * @return bool
     */
    public function hasRecipientList(RecipientList $recipientList)
    {
        return $this->recipientLists->contains($recipientList);
    }

    /**
     * Adds the recipient.
     *
     * @param RecipientList $recipientList
     * @return Recipient
     */
    public function addRecipientList(RecipientList $recipientList)
    {
        if (!$this->hasRecipientList($recipientList)) {
            $recipientList->addRecipient($this);
            $this->recipientLists->add($recipientList);
        }
        return $this;
    }

    /**
     * Removes the recipient list.
     *
     * @param RecipientList $recipientList
     * @return Recipient
     */
    public function removeRecipientList(RecipientList $recipientList)
    {
        if (!$this->hasRecipientList($recipientList)) {
            $this->recipientLists->removeElement($recipientList);
        }
        return $this;
    }

    /**
     * Sets the recipientLists.
     *
     * @param ArrayCollection $recipientLists
     * @return Recipient
     */
    public function setRecipientLists($recipientLists)
    {
        $this->recipientLists = $recipientLists;
        return $this;
    }
}
