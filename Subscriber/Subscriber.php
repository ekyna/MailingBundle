<?php

namespace Ekyna\Bundle\MailingBundle\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Ekyna\Bundle\MailingBundle\Entity\RecipientRepository;
use Ekyna\Bundle\UserBundle\Entity\UserRepository;
use Ekyna\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Subscriber
 * @package Ekyna\Bundle\MailingBundle\Subscriber
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Subscriber implements SubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RecipientRepository
     */
    protected $recipientRepository;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserRepository $userRepository,
        RecipientRepository $recipientRepository
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->recipientRepository = $recipientRepository;
    }

    /**
     * Synchronizes the recipient's data with the given user.
     *
     * @param UserInterface $user
     */
    public function synchronizeByUser(UserInterface $user)
    {
        if (null !== $recipient = $this->findRecipientByUser($user)) {
            $doPersist = false;
            if ($user !== $recipient->getUser()) {
                $recipient->setUser($user);
                $doPersist = true;
            } elseif ($user->getEmail() != $recipient->getEmail()) {
                $recipient->setEmail($user->getEmail());
                $doPersist = true;
            }

            if ($user->getFirstName() !== $recipient->getFirstName()) {
                $recipient->setFirstName($user->getFirstName());
                $doPersist = true;
            }
            if ($user->getLastName() !== $recipient->getLastName()) {
                $recipient->setLastName($user->getLastName());
                $doPersist = true;
            }

            if ($doPersist && $this->validator->validate($recipient)) {
                $this->em->persist($recipient);
                $this->em->flush($recipient);
            }
            // TODO validation errors
        }
    }

    /**
     * Synchronizes the user's data with the given recipient.
     *
     * @param Recipient $recipient
     */
    public function synchronizeByRecipient(Recipient $recipient)
    {
        if (null === $recipient->getUser() && null !== $user = $this->findUserByRecipient($recipient)) {
            $recipient->setUser($user);

            if ($user->getFirstName() !== $recipient->getFirstName()) {
                $recipient->setFirstName($user->getFirstName());
            }
            if ($user->getLastName() !== $recipient->getLastName()) {
                $recipient->setLastName($user->getLastName());
            }

            if ($this->validator->validate($recipient)) {
                $this->em->persist($recipient);
                $this->em->flush($recipient);
            }
            // TODO validation errors
        }
    }

    /**
     * Finds the recipient matching the given user.
     *
     * @param UserInterface $user
     * @return null|Recipient
     */
    protected function findRecipientByUser(UserInterface $user)
    {
        if (null !== $recipient = $this->recipientRepository->findOneBy(array('user' => $user))) {
            return $recipient;
        }
        return $this->recipientRepository->findOneBy(array('email' => $user->getEmail()));
    }

    /**
     * Finds the user matching the given recipient.
     *
     * @param Recipient $recipient
     * @return null|UserInterface
     */
    protected function findUserByRecipient(Recipient $recipient)
    {
        if (null !== $user = $recipient->getUser()) {
            return $this->userRepository->findOneBy(array('id' => $user->getId()));
        }
        return $this->userRepository->findOneBy(array('email' => $recipient->getEmail()));
    }
}
