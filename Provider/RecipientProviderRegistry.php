<?php

namespace Ekyna\Bundle\MailingBundle\Provider;

/**
 * Class RecipientProviderRegistry
 * @package Ekyna\Bundle\MailingBundle\Provider
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RecipientProviderRegistry
{
    /**
     * @var array|RecipientProviderInterface[]
     */
    private $providers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->providers = [];
    }

    /**
     * Adds the provider.
     *
     * @param RecipientProviderInterface $provider
     * @throws \RuntimeException
     */
    public function addProvider(RecipientProviderInterface $provider)
    {
        $name = $provider->getName();
        if (array_key_exists($name, $this->providers)) {
            throw new \RuntimeException(sprintf('Provider "%s" is already registered.', $name));
        }
        $this->providers[$name] = $provider;
    }

    /**
     * Returns the provider by name.
     *
     * @param string $name
     * @return RecipientProviderInterface
     * @throws \RuntimeException
     */
    public function getProvider($name)
    {
        if (!array_key_exists($name, $this->providers)) {
            throw new \RuntimeException(sprintf('Unknown "%s" recipient provider.', $name));
        }
        return $this->providers[$name];
    }

    /**
     * Returns the providers.
     *
     * @return array|RecipientProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
