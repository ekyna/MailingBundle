<?php

namespace Ekyna\Bundle\MailingBundle\Execution;

use Ekyna\Bundle\MailingBundle\Entity\RecipientExecution;
use Ekyna\Bundle\MailingBundle\Exception\MailingException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Renderer
 * @package Ekyna\Bundle\MailingBundle\Execution
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Renderer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var string
     */
    private $config;


    /**
     * Constructor.
     *
     * @param \Twig_Environment     $twig
     * @param UrlGeneratorInterface $urlGenerator
     * @param array                 $templates
     * @param array                 $config
     */
    public function __construct(\Twig_Environment $twig, UrlGeneratorInterface $urlGenerator, array $templates, array $config)
    {
        $this->twig      = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->templates = $templates;
        $this->config    = $config;
    }

    /**
     * Renders the recipient execution email.
     *
     * @param RecipientExecution $recipientExecution
     * @return string            The email content
     * @throws MailingException  If the template can't be found.
     */
    public function render(RecipientExecution $recipientExecution)
    {
        $campaign = $recipientExecution->getExecution()->getCampaign();

        $template = $this->getTemplate($campaign->getTemplate());

        $content = $this->twig->render($template, array(
            'campaign'  => $campaign,
            'recipient' => $recipientExecution->getRecipient(),
        ));

        // Append visit tracker token to all anchor's href attribute.
        $token = $recipientExecution->getToken();
        $content = preg_replace_callback(
            '/(<a\s+[^>]*href=")([^"]*)("[^>]*>)/i',
            function ($matches) use ($token) {
                array_shift($matches);
                // TODO edit only links that points to the site's hostname ?
                if (preg_match('/\?[^"]+/i', $matches[1])) {
                    $matches[1] .= '&'.$this->config['visit_param'].'='.$token;
                } else {
                    $matches[1] .= '?'.$this->config['visit_param'].'='.$token;
                }
                return implode($matches);
            },
            $content
        );

        // Append open tracker image
        $openTrackerUrl = $this->urlGenerator->generate('ekyna_mailing_tracker_open', array(
            $this->config['open_param'] => $token,
        ), true);
        $img = '<img src="' . $openTrackerUrl . '" width="1" height="1">';
        $content = str_replace('</body>', $img.'</body>', $content);

        return $content;
    }

    /**
     * Returns the twig template name.
     *
     * @param $key
     * @return string
     * @throws MailingException If the template can't be found.
     */
    private function getTemplate($key)
    {
        if (!array_key_exists($key, $this->templates)) {
            throw new MailingException(sprintf('Template "%s" not found.', $key));
        }
        return $this->templates[$key]['path'];
    }
}
