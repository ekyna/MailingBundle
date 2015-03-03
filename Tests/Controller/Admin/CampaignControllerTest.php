<?php

namespace Ekyna\Bundle\MailingBundle\Tests\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Tests\Controller\ResourceControllerTest;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

/**
 * Class CampaignControllerTest
 * @package Ekyna\Bundle\MailingBundle\Tests\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignControllerTest extends ResourceControllerTest
{
    /**
     * @var string
     */
    protected $configurationId = 'ekyna_mailing.campaign.configuration';

    /**
     * {@inheritdoc}
     */
    protected function assertList(Crawler $crawler)
    {
        $this->assertCount(4, $crawler->filter('a:contains("Campaign")'));
    }

    /**
     * {@inheritdoc}
     */
    protected function fillNewForm(Form $form)
    {
        // General
        $form['ekyna_mailing_campaign[name]'] = 'Test new campaign name';
        $form['ekyna_mailing_campaign[fromEmail]'] = 'test@example.org';
        $form['ekyna_mailing_campaign[fromName]'] = 'Test from name';
        $form['ekyna_mailing_campaign[subject]'] = 'Test subject';
        $form['ekyna_mailing_campaign[template]']->select('EkynaMailingBundle::default_template.html.twig');
        $form['ekyna_mailing_campaign[content]'] = '<p>Test content</p>';
    }

    /**
     * {@inheritdoc}
     */
    protected function assertShowAfterNew(Crawler $crawler)
    {
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test new campaign name")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("test@example.org")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test from name")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test subject")'));
    }

    /**
     * {@inheritdoc}
     */
    protected function assertShow(Crawler $crawler)
    {
        $this->assertEquals(1, $crawler->filter('div.show-widget:contains("Campaign test name")')->count());
    }

    /**
     * {@inheritdoc}
     */
    protected function fillEditForm(Form $form)
    {
        // General
        $form['ekyna_mailing_campaign[name]'] = 'Test edit campaign name';
        $form['ekyna_mailing_campaign[fromEmail]'] = 'test-edit@example.org';
        $form['ekyna_mailing_campaign[fromName]'] = 'Test edit from name';
        $form['ekyna_mailing_campaign[subject]'] = 'Test edit subject';
    }

    /**
     * {@inheritdoc}
     */
    protected function assertShowAfterEdit(Crawler $crawler)
    {
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test edit campaign name")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("test-edit@example.org")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test edit from name")'));
        $this->assertCount(1, $crawler->filter('div.show-widget:contains("Test edit subject")'));
    }

    /**
     * {@inheritdoc}
     */
    protected function assertListAfterRemove(Crawler $crawler)
    {
        $this->assertCount(0, $crawler->filter('a:contains("Test edit campaign name")'));
    }
}
