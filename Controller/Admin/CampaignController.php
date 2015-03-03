<?php

namespace Ekyna\Bundle\MailingBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Context;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Ekyna\Bundle\MailingBundle\Entity\RecipientExecution;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CampaignController
 * @package Ekyna\Bundle\MailingBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CampaignController extends ResourceController
{
    /**
     * Renders the campaign content with fake recipient.
     *
     * @param Request $request
     * @return Response
     */
    public function contentAction(Request $request)
    {
        $context = $this->loadContext($request);
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Campaign $campaign */
        $campaign = $context->getResource();

        $this->isGranted('VIEW', $campaign);

        // Fake execution
        $execution = new Execution();
        $execution
            ->setCampaign($campaign)
        ;
        $recipient = new Recipient();
        $recipient
            ->setEmail('john.doe@example.org')
            ->setFirstName('John')
            ->setLastName('Doe')
        ;
        $recipientExecution = new RecipientExecution();
        $recipientExecution
            ->setExecution($execution)
            ->setRecipient($recipient)
            ->setToken(hash('md5', uniqid()))
        ;

        $content = $this->get('ekyna_mailing.execution.renderer')->render($recipientExecution);

        return new Response($content);
    }

    /**
     * Creates a new campaign.
     *
     * @param Context $context
     * @return \Ekyna\Bundle\MailingBundle\Entity\Campaign
     */
    protected function createNew(Context $context)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Entity\Campaign $campaign */
        $campaign = parent::createNew($context);

        // TODO move in repo ?
        $settings = $this->get('ekyna_setting.manager');

        $campaign
            ->setFromEmail($settings->getParameter('notify.from_email'))
            ->setFromName($settings->getParameter('notify.form_name'))
        ;

        return $campaign;
    }
}
