<?php

namespace Ekyna\Bundle\MailingBundle\Command;

use Ekyna\Bundle\MailingBundle\Entity\Execution;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CronCommand
 * @package Ekyna\Bundle\MailingBundle\Command
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CronCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:mailing:cron')
            ->setDescription('Launches automated mailing campaigns executions.')
            ->setHelp(<<<EOT
The <info>ekyna:mailing:cron</info> launches automated mailing campaigns executions :

<info>php app/console ekyna:mailing:cron</info>

<comment>This command should be configured in cron tab.</comment>
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(Input\InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $container->enterScope('request');
        $container->set('request', new Request(), 'request');

        $repository = $container->get('ekyna_mailing.execution.repository');

        // Check if there are running automated executions.
        $count = $repository->countRunningAutomated();
        if (0 < $count) {
            // We don't want to have more than one execution running.
            return;
        }

        // Starts the first paused automated execution.
        $pausedExecution = $repository->findOnePausedAutomated();
        if (null !== $pausedExecution) {
            $this->runExecution($pausedExecution);
            return;
        }

        // Starts the first pending automated execution.
        $pendingExecution = $repository->findOnePendingAutomated();
        if (null !== $pendingExecution) {
            $this->runExecution($pendingExecution);
            return;
        }
    }

    /**
     * Runs the given execution.
     *
     * @param Execution $execution
     */
    private function runExecution(Execution $execution)
    {
        $operator = $this->getContainer()->get('ekyna_mailing.execution.operator');

        $event = $operator->start($execution);
        if (!$event->isPropagationStopped()) {
            $runner = $this->getContainer()->get('ekyna_mailing.execution.runner');
            $runner->run($execution);
        }
    }
}
