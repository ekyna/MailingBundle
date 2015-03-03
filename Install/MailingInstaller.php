<?php

namespace Ekyna\Bundle\MailingBundle\Install;

use Ekyna\Bundle\InstallBundle\Install\OrderedInstallerInterface;
use Ekyna\Bundle\MailingBundle\Entity\RecipientList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailingInstaller
 * @package Ekyna\Bundle\MailingBundle\Install
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MailingInstaller implements OrderedInstallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(ContainerInterface $container, Command $command, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>[Mailing] Creating default recipient list:</info>');
        $this->createDefaultList($container, $output);
        $output->writeln('');
    }

    /**
     * Creates the default recipient list.
     *
     * @param ContainerInterface $container
     * @param OutputInterface $output
     */
    private function createDefaultList(ContainerInterface $container, OutputInterface $output)
    {
        $name = $container->getParameter('ekyna_mailing.default_list');

        $repository = $container->get('ekyna_mailing.recipientlist.repository');

        $output->write(sprintf(
            '- <comment>%s</comment> %s ',
            $name,
            str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
        ));

        if (null === $list = $repository->findOneBy(['name' => $name])) {
            $list = new RecipientList();
            $list->setName($name);

            $em = $container->get('ekyna_mailing.recipientlist.manager');
            $em->persist($list);
            $em->flush();

            $output->writeln('done.');
        } else {
            $output->writeln('already exists.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 99;
    }
}
