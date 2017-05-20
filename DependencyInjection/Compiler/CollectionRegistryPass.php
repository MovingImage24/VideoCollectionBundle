<?php

namespace MovingImage\Bundle\VideoCollection\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CollectionRegistryPass.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class CollectionRegistryPass implements CompilerPassInterface
{
    /**
     * Store all tagged video collection service definitions in our
     * definition registry, together with any custom tag attributes
     * associated with the service definitions.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('video_collections.registry')) {
            return;
        }

        $definition = $container->getDefinition('video_collections.registry');
        $taggedServices = $container->findTaggedServiceIds('video_collections.collection');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addCollection', array(
                new Reference($id),
                $attributes
            ));
        }
    }
}
