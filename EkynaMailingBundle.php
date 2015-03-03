<?php

namespace Ekyna\Bundle\MailingBundle;

use Ekyna\Bundle\MailingBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EkynaMailingBundle
 * @package Ekyna\Bundle\MailingBundle
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaMailingBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new Compiler\AdminMenuPass())
            ->addCompilerPass(new Compiler\RecipientProviderPass())
        ;
    }
}
