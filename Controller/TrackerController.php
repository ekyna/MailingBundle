<?php

namespace Ekyna\Bundle\MailingBundle\Controller;

use Ekyna\Bundle\CoreBundle\Controller\Controller;
use Ekyna\Bundle\CoreBundle\Http\TransparentPixelResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TrackerController
 * @package Ekyna\Bundle\MailingBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TrackerController extends Controller
{
    public function trackOpenAction(Request $request)
    {
        $param = $this->container->getParameter('ekyna_mailing.tracker_config')['open_param'];

        if (0 < strlen($token = $request->query->get($param))) {

            $reClass = $this->container->getParameter('ekyna_mailing.recipientExecution.class');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository($reClass);
            $recipientExecution = $repository->findOneBy(['token' => $token]);

            if (null !== $recipientExecution) {
                $sm = $this->get('sm.factory')->get($recipientExecution);
                if ($sm->can('open')) {
                    $sm->apply('open');

                    $em->persist($recipientExecution);
                    $em->flush();
                }
            }
        }

        return new TransparentPixelResponse();
    }
}
