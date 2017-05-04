<?php

namespace MovingImage\Bundle\VideoCollection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use MovingImage\VideoCollection\Entity\Collection;

/**
 * Class ServiceDefinitionFactory.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class ServiceDefinitionFactory
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $prefix;

    /**
     * ServiceDefinitionFactory constructor.
     *
     * VERY IMPORTANT: Make sure to NEVER WRITE to the Container - it's only injected
     * so that you can RETRIEVE definitions when necessary.
     *
     * @param ContainerBuilder $container
     * @param string           $prefix
     */
    public function __construct(ContainerBuilder $container, $prefix)
    {
        $this->prefix = $prefix;
        $this->container = $container;
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Retrieve the previously defined data provider service definition
     * by name.
     *
     * @param $dataProviderName
     *
     * @return Definition
     *
     * @throws \Exception
     */
    public function getDataProviderDefinition($dataProviderName)
    {
        $container = $this->getContainer();
        $serviceName = sprintf('data_providers.%s', $dataProviderName);

        if (!$container->hasDefinition($serviceName)) {
            throw new \Exception(sprintf('Collection data provider %s is not available.', $dataProviderName));
        }

        return $container->getDefinition($serviceName);
    }

    /**
     * Create a new service definition instance for a Collection.
     *
     * @param string $name
     * @param array  $options
     *
     * @return Definition
     *
     * @throws \Exception
     */
    public function createCollectionDefinition($name, array $options)
    {
        if (!isset($options['data_provider'])) {
            throw new \Exception(sprintf('Collection \'%s\' does not have a data provider configured.', $name));
        }

        $definition = new Definition(Collection::class, [
            $this->getDataProviderDefinition($options['data_provider']),
            $name,
            $options,
        ]);

        // Append service tag, which contains optionally
        // video collection tags for the registry.
        $attributes = [];

        if (isset($options['tags'])) {
            $attributes = [
                'collection_tags' => $options['tags'],
            ];
        }

        $definition->addTag('video_collection.collection', $attributes);

        return $definition;
    }
}
