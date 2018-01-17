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

        // services fetched from the container need to be public
        $definition->setPublic(true);

        // Append service tag, but due to the nature of Symfony's tag attribute
        // design (must be scalar), we must add one tag per collection tag in
        // the options. I think this is pretty dumb design tbh.
        //
        // To illustrate, if we were to define this:
        //
        // services:
        //   my_service:
        //     tags:
        //       - [name: my_tag, collection_tag: bla]
        //       - [name: my_tag, collection_tag: bla2]
        //       - [name: my_tag, collection_tag: bla3]
        //
        // We would end up with the registry method being called only once
        // and $attributes would be this:
        //
        // [
        //    [0] => ['collection_tag' => 'bla'],
        //    [1] => ['collection_tag' => 'bla2'],
        //    [2] => ['collection_tag' => 'bla3'],
        // ]
        if (isset($options['tags'])) {
            foreach ($options['tags'] as $tag) {
                $definition->addTag('video_collections.collection', ['collection_tag' => $tag]);
            }
        } else {
            // When there's no tags, add it to the registry without tags.
            $definition->addTag('video_collections.collection');
        }

        return $definition;
    }
}
