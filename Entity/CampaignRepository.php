<?php

namespace Ekyna\Bundle\MailingBundle\Entity;

use Ekyna\Bundle\AdminBundle\Doctrine\ORM\ResourceRepository;
use Ekyna\Bundle\SettingBundle\Manager\SettingsManagerInterface;

/**
 * Class CampaignRepository
 * @package Ekyna\Bundle\MailingBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignRepository extends ResourceRepository
{
    /**
     * @var SettingsManagerInterface
     */
    private $settings;


    /**
     * @param SettingsManagerInterface $settings
     */
    public function setSettingsManager(SettingsManagerInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        $campaign = parent::createNew();

        $campaign
            ->setFromEmail($this->settings->getParameter('notification.from_email'))
            ->setFromName($this->settings->getParameter('notification.from_name'))
        ;

        return $campaign;
    }
}
