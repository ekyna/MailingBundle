<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

/**
 * Class RecipientExecution
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientExecution
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Recipient
     */
    protected $recipient;

    /**
     * @var Execution
     */
    protected $execution;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $state;


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
     * Set recipient
     *
     * @param Recipient $recipient
     * @return RecipientExecution
     */
    public function setRecipient(Recipient $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set execution
     *
     * @param Execution $execution
     * @return RecipientExecution
     */
    public function setExecution(Execution $execution)
    {
        $this->execution = $execution;

        return $this;
    }

    /**
     * Get execution
     *
     * @return Execution
     */
    public function getExecution()
    {
        return $this->execution;
    }

    /**
     * Returns the token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the token.
     *
     * @param string $token
     * @return RecipientExecution
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return RecipientExecution
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
}
