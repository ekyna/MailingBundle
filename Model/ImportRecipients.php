<?php

namespace Ekyna\Bundle\MailingBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImportRecipients
 * @package Ekyna\Bundle\MailingBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportRecipients
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     */
    private $delimiter = ';';

    /**
     * @var string
     */
    private $enclosure = '"';

    /**
     * @var int
     */
    private $emailColNum = 1;

    /**
     * @var int
     */
    private $firstNameColNum;

    /**
     * @var int
     */
    private $lastNameColNum;

    /**
     * Returns the file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file.
     *
     * @param UploadedFile $file
     * @return ImportRecipients
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Returns the delimiter.
     *
     * @return string
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Sets the delimiter.
     *
     * @param string $delimiter
     * @return ImportRecipients
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Returns the enclosure.
     *
     * @return string
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * Sets the enclosure.
     *
     * @param string $enclosure
     * @return ImportRecipients
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Returns the emailColNum.
     *
     * @return int
     */
    public function getEmailColNum()
    {
        return $this->emailColNum;
    }

    /**
     * Sets the emailColNum.
     *
     * @param int $emailColNum
     * @return ImportRecipients
     */
    public function setEmailColNum($emailColNum)
    {
        $this->emailColNum = $emailColNum;
        return $this;
    }

    /**
     * Returns the firstNameColNum.
     *
     * @return int
     */
    public function getFirstNameColNum()
    {
        return $this->firstNameColNum;
    }

    /**
     * Sets the firstNameColNum.
     *
     * @param int $firstNameColNum
     * @return ImportRecipients
     */
    public function setFirstNameColNum($firstNameColNum)
    {
        $this->firstNameColNum = $firstNameColNum;
        return $this;
    }

    /**
     * Returns the lastNameColNum.
     *
     * @return int
     */
    public function getLastNameColNum()
    {
        return $this->lastNameColNum;
    }

    /**
     * Sets the lastNameColNum.
     *
     * @param int $lastNameColNum
     * @return ImportRecipients
     */
    public function setLastNameColNum($lastNameColNum)
    {
        $this->lastNameColNum = $lastNameColNum;
        return $this;
    }
}
