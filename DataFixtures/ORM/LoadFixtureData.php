<?php

namespace Ekyna\Bundle\MailingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ekyna\Bundle\CmsBundle\DataFixtures\ORM\DataFixtureLoader;

/**
 * Class LoadFixtureData
 * @package Ekyna\Bundle\MailingBundle\DataFixtures\ORM
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class LoadFixtureData extends DataFixtureLoader implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return array(__DIR__.'/fixtures.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 99; // the order in which fixtures will be loaded
    }
}
