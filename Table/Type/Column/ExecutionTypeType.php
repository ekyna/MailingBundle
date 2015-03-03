<?php

namespace Ekyna\Bundle\MailingBundle\Table\Type\Column;

use Ekyna\Bundle\MailingBundle\Model\ExecutionTypes;
use Ekyna\Component\Table\Extension\Core\Type\Column\PropertyType;
use Ekyna\Component\Table\Table;
use Ekyna\Component\Table\View\Cell;

/**
 * Class ExecutionTypeType
 * @package Ekyna\Bundle\MailingBundle\Table\Type\Column
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ExecutionTypeType extends PropertyType
{
    /**
     * {@inheritdoc}
     */
    public function buildViewCell(Cell $cell, Table $table, array $options)
    {
        parent::buildViewCell($cell, $table, $options);

        $state = $cell->vars['value'];

        $cell->setVars(array(
            'route' => null,
            'label' => ExecutionTypes::getLabel($state),
            'class' => 'label-'.ExecutionTypes::getTheme($state),
            'type'  => 'boolean'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_mailing_execution_type';
    }
}
