<?php

namespace MovingImage\Bundle\VideoCollection\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class VideoCollectionExtension.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoCollectionExtension extends ConfigurableExtension
{
    private function mergeDefaultsAndDefinitions($defaults, $definitions)
    {
        foreach ($definitions as $key => $collection) {
            foreach ($defaults as $defaultKey => $defaultValues) {
                if ($collection['data_provider'] == $defaultKey) {
                    $definitions[$key] = array_merge($defaultValues, $collection);
                }
            }
        }

        return $definitions;
    }

    /**
     * Define our collections as container parameter so that our compiler
     * pass and bundle classes can use the configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $configs, ContainerBuilder $container)
    {

        $container->setParameter('video_collections',
            $this->mergeDefaultsAndDefinitions($configs['defaults'], $configs['collections']));

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');
    }
}
