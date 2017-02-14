<?php

namespace MovingImage\Bundle\VideoCollection;

use MovingImage\Bundle\VideoCollection\DependencyInjection\Compiler\CollectionServiceDefinitionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class VideoCollectionBundle.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoCollectionBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CollectionServiceDefinitionPass());
    }
}
