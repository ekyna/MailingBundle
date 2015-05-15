<?php

namespace Ekyna\Bundle\MailingBundle\Install;

use Ekyna\Bundle\InstallBundle\Install\OrderedInstallerInterface;
use Ekyna\Bundle\MailingBundle\Entity\RecipientList;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MailingInstaller
 * @package Ekyna\Bundle\MailingBundle\Install
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MailingInstaller implements OrderedInstallerInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function install(Command $command, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>[Mailing] Creating default recipient list:</info>');
        $this->createDefaultList($output);
        $output->writeln('');
    }

    /**
     * Creates the default recipient list.
     *
     * @param OutputInterface $output
     */
    private function createDefaultList(OutputInterface $output)
    {
        $name = $this->container->getParameter('ekyna_mailing.default_list');

        $repository = $this->container->get('ekyna_mailing.recipientlist.repository');

        $output->write(sprintf(
            '- <comment>%s</comment> %s ',
            $name,
            str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
        ));

        if (null === $list = $repository->findOneBy(['name' => $name])) {
            $list = new RecipientList();
            $list->setName($name);

            $em = $this->container->get('ekyna_mailing.recipientlist.manager');
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
