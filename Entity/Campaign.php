<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Campaign
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Campaign
{
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
    protected $fromEmail;

    /**
     * @var string
     */
    protected $fromName;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var ArrayCollection
     */
    protected $executions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->executions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Campaign
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

    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     * @return Campaign
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Get fromEmail
     *
     * @return string 
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set fromName
     *
     * @param string $fromName
     * @return Campaign
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get fromName
     *
     * @return string 
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Campaign
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Campaign
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Campaign
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the executions.
     *
     * @return ArrayCollection
     */
    public function getExecutions()
    {
        return $this->executions;
    }

    /**
     * Returns whether the campaign has the given execution or not.
     *
     * @param Execution $execution
     * @return bool
     */
    public function hasExecution(Execution $execution)
    {
        return $this->executions->contains($execution);
    }

    /**
     * Adds the execution.
     *
     * @param Execution $execution
     * @return Campaign
     */
    public function addExecution(Execution $execution)
    {
        if (!$this->hasExecution($execution)) {
            $this->executions->add($execution);
        }
        return $this;
    }

    /**
     * Removes the the execution.
     *
     * @param Execution $execution
     * @return Campaign
     */
    public function removeExecution(Execution $execution)
    {
        if ($this->hasExecution($execution)) {
            $this->executions->removeElement($execution);
        }
        return $this;
    }

    /**
     * Sets the executions.
     *
     * @param ArrayCollection $executions
     * @return Campaign
     */
    public function setExecutions(ArrayCollection $executions)
    {
        $this->executions = $executions;
        return $this;
    }

}
