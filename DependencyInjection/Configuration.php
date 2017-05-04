<?php

namespace MovingImage\Bundle\VideoCollection\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Define configuration structure for this bundle.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('video_collections');

        $rootNode
            ->children()
                ->arrayNode('defaults')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('vm_id')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('collections')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('data_provider')
                                ->defaultValue('vmpro')
                            ->end()
                            ->scalarNode('id')
                            ->end()
                            ->scalarNode('embed_code_id')
                            ->end()
                            ->scalarNode('channel_id')
                            ->end()
                            ->arrayNode('channel_ids')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->scalarNode('search_term')
                            ->end()
                            ->scalarNode('search_field')
                            ->end()
                            ->scalarNode('order_property')
                            ->end()
                            ->scalarNode('vm_id')
                            ->end()
                            ->integerNode('limit')
                                ->defaultValue(12)
                            ->end()
                            ->integerNode('offset')
                            ->end()
                            ->scalarNode('order_by')
                            ->end()
                            ->enumNode('order')
                                ->values(['desc', 'asc', null])
                            ->end()
                            ->arrayNode('filter')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->booleanNode('include_sub_channels')
                            ->end()
                            ->enumNode('publication_state')
                                ->values(['published', 'not_published', 'all', null])
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
