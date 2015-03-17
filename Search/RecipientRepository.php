<?php

namespace Ekyna\Bundle\MailingBundle\Search;

use Ekyna\Bundle\AdminBundle\Search\SearchRepositoryInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;

/**
 * Class RecipientRepository
 * @package Ekyna\Bundle\MailingBundle\Search
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientRepository extends Repository implements SearchRepositoryInterface
{
    /**
     * Search recipients.
     *
     * @param string $text
     * @param integer $limit
     * @return \Ekyna\Bundle\MailingBundle\Entity\Recipient[]
     */
    public function defaultSearch($text, $limit = 10)
    {
        if (0 == strlen($text)) {
            $query = new Query\MatchAll();
        } else {
            $query = new Query\MultiMatch();
            $query
                ->setQuery($text)
                ->setFields(array('email', 'first_name', 'last_name'))
            ;
        }

        return $this->find($query, $limit);
    }
}
