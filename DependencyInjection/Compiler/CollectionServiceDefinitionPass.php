<?php
namespace MovingImage\Bundle\VideoCollection\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CollectionServiceDefinitionPass.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class CollectionServiceDefinitionPass implements CompilerPassInterface
{
    /**
     * Attempt to register a definition in the Container for each configured
     * video collection in config.yml.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definitionFactory = $container->get('video_collections.service_definition_factory');

        foreach ($container->getParameter('video_collections') as $key => $collectionArr) {
            $container->setDefinition(
                sprintf('%s.collections.%s', $definitionFactory->getPrefix(), $key),
                $definitionFactory->createCollectionDefinition($key, $collectionArr)
            );
        }
    }
}
