<?php

namespace Ekyna\Bundle\MailingBundle\Exception;

/**
 * Class ImportProviderException
 * @package Ekyna\Bundle\MailingBundle\Exception
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportProviderException extends ProviderException
{
    /**
     * @var null|string
     */
    private $propertyPath;

    /**
     * Constructor.
     *
     * @param string $message
     * @param string $propertyPath
     */
    public function __construct($message, $propertyPath = null)
    {
        parent::__construct($message);

        $this->propertyPath = $propertyPath;
    }

    /**
     * Returns the property path.
     *
     * @return null|string
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }
}
