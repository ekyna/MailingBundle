<?php

namespace Ekyna\Bundle\MailingBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RunCommand
 * @package Ekyna\Bundle\MailingBundle\Command
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RunCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ekyna:mailing:run')
            ->setDescription('Runs the mailing campaign execution.')
            ->addArgument('id', Input\InputArgument::REQUIRED, 'The campaign execution id')
            ->setHelp(<<<EOT
The <info>ekyna:mailing:run</info> runs the mailing campaign execution:

<info>php app/console ekyna:mailing:run [The campaign execution id]</info>

<comment>This command is run internally and should never be used manually.</comment>
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(Input\InputInterface $input, OutputInterface $output)
    {
        $id = intval($input->getArgument('id'));

        if (0 >= $id) {
            $output->writeln('<error>Invalid [id] argument.</error>');
        }

        $container = $this->getContainer();

        $container->enterScope('request');
        $container->set('request', new Request(), 'request');

        $repository = $container->get('ekyna_mailing.execution.repository');

        /** @var \Ekyna\Bundle\MailingBundle\Entity\Execution $execution */
        if (null === $execution = $repository->find($id)) {
            $output->writeln(sprintf('<errors>Campaign execution #%d not found.</errors>', $id));
        }

        $container->get('ekyna_mailing.execution.runner')->run($execution);
    }
}
