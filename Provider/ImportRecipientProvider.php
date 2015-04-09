<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

use Ekyna\Bundle\MailingBundle\Entity\Recipient;
use Ekyna\Bundle\MailingBundle\Exception\ImportProviderException;
use Ekyna\Bundle\MailingBundle\Model\ImportRecipients;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ImportRecipientProvider
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImportRecipientProvider extends AbstractRecipientProvider
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param KernelInterface     $kernel
     * @param TranslatorInterface $translator
     */
    public function __construct(KernelInterface $kernel, TranslatorInterface $translator)
    {
        $this->kernel = $kernel;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm($action)
    {
        $form = $this->formFactory
            ->create('ekyna_mailing_import_recipient', new ImportRecipients(), array(
                'action' => $action,
                'attr' => array('class' => 'form-horizontal'),
            ))
            ->add('actions', 'form_actions', [
                'buttons' => [
                    'save' => ['type' => 'submit', 'options' => ['label' => 'ekyna_core.button.import']],
                ]
            ])
        ;

        $this->setForm($form);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $form = $this->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            return $this->import($form);
        }

        return false;
    }

    /**
     * Imports the recipients.
     *
     * @param FormInterface $form
     * @return Recipient[]|false
     */
    private function import(FormInterface $form)
    {
        /** @var \Ekyna\Bundle\MailingBundle\Model\ImportRecipients $import */
        $import = $form->getData();

        $handle = false;
        $dir = rtrim($this->kernel->getRootDir(), '/') . '/../tmp';
        $fileName = 'recipient-import-'.date('Y-m-d-H-i').'.csv';
        $filePath = $dir.'/'.$fileName;

        $import->getFile()->move($dir, $fileName);

        $result = false;

        try {
            if (false !== $handle = fopen($filePath, "r")) {
                $recipients = [];
                $count = 0;
                $emailErrorCount = 0;

                $emailColNum = intval($import->getEmailColNum()) - 1;

                $firstNameColNum = $import->getFirstNameColNum();
                $firstNameColNum = null !== $firstNameColNum ? intval($firstNameColNum) - 1 : false;

                $lastNameColNum = $import->getLastNameColNum();
                $lastNameColNum = null !== $lastNameColNum ? intval($lastNameColNum) - 1 : false;

                $delimiter = $import->getDelimiter();
                $enclosure = $import->getEnclosure();

                while (false !== $data = fgetcsv($handle, 2048, $delimiter, $enclosure)) {

                    // Test columns
                    if (0 == $count) {
                        if (!array_key_exists($emailColNum, $data)) {
                            throw new ImportProviderException(
                                'ekyna_mailing.recipient_provider.import.message.column_not_exists',
                                'emailColNum'
                            );
                        }
                        if (false !== $firstNameColNum && !array_key_exists($firstNameColNum, $data)) {
                            throw new ImportProviderException(
                                'ekyna_mailing.recipient_provider.import.message.column_not_exists',
                                'firstNameColNum'
                            );
                        }
                        if (false !== $lastNameColNum && !array_key_exists($lastNameColNum, $data)) {
                            throw new ImportProviderException(
                                'ekyna_mailing.recipient_provider.import.message.column_not_exists',
                                'lastNameColNum'
                            );
                        }
                    }

                    $email = trim($data[$emailColNum]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErrorCount++;
                        if (5 < $emailErrorCount) {
                            throw new ImportProviderException(
                                'ekyna_mailing.recipient_provider.import.message.no_email',
                                'emailColNum'
                            );
                        }
                        continue;
                    }

                    $recipient = new Recipient();
                    $recipient->setEmail($email);

                    if (false !== $firstNameColNum && false !== $lastNameColNum) {
                        $firstName = trim($data[$firstNameColNum]);
                        $lastName = trim($data[$lastNameColNum]);
                        if (0 < strlen($firstName) && 0 < strlen($lastName)) {
                            $recipient
                                ->setFirstName($firstName)
                                ->setLastName($lastName)
                            ;
                        }
                    }

                    $recipients[] = $recipient;

                    $count++;
                }

                if (0 < count($recipients)) {
                    $result = $recipients;
                } else {
                    throw new ImportProviderException(
                        'ekyna_mailing.recipient_provider.import.message.no_recipient',
                        'file'
                    );
                }

            } else {
                throw new ImportProviderException(
                    'ekyna_mailing.recipient_provider.import.message.unreadable_file',
                    'file'
                );
            }

        } catch(ImportProviderException $e) {
            if (null !== $pp = $e->getPropertyPath()) {
                $form->get($pp)->addError(new FormError($this->trans($e->getMessage())));
            } else {
                $form->addError(new FormError($this->trans($e->getMessage())));
            }
        }

        if (false !== $handle) {
            fclose($handle);
        }
        @unlink($filePath);

        return $result;
    }

    /**
     * Translates the message id.
     *
     * @param $id
     * @return string
     */
    private function trans($id)
    {
        return $this->translator->trans($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTemplate()
    {
        return 'EkynaMailingBundle:Admin/Provider:import_recipient_form.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'ekyna_mailing.recipient_provider.import.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'import_recipient_provider';
    }
}
