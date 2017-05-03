<?php

namespace MovingImage\Bundle\VideoCollection\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use MovingImage\Bundle\VideoCollection\DependencyInjection\VideoCollectionExtension;
use Symfony\Component\Yaml\Parser;

class VideoCollectionExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns a full configuration.
     *
     * @return array
     */
    private function getFullConfig()
    {
        $yml = <<<'EOF'
video_collection:
    collections:
        collection_name:
            channel_id: 123
            vm_id: 456
            limit: 4
            order: asc
            order_property: created_date
            filter:
                is_featured: true
            data_provider: vmsix
            search_term: abc
EOF;
        $parser = new Parser();

        return $parser->parse($yml);
    }

    /**
     * Returns an empty configuration.
     *
     * @return array
     */
    private function getEmptyConfig()
    {
        $yml = <<<'EOF'
video_collection:
    collections:
        collection_name:
            vm_id: 456
EOF;
        $parser = new Parser();

        return $parser->parse($yml);
    }

    /**
     * Test if configuration set is successfully written as parameters in the container.
     */
    public function testConfig()
    {
        $config = $this->getFullConfig();
        $videoCollectionExtension = new VideoCollectionExtension();
        $container = new ContainerBuilder();

        $videoCollectionExtension->load($config, $container);
        $videoCollections = $container->getParameter('video_collections');

        $expectedCollections = [
            'collection_name' => [
                'channel_id' => 123,
                'vm_id' => 456,
                'limit' => 4,
                'order' => 'asc',
                'order_property' => 'created_date',
                'filter' => [
                    'is_featured' => true,
                ],
                'data_provider' => 'vmsix',
                'search_term' => 'abc',
            ],
        ];

        $this->assertEquals($expectedCollections, $videoCollections);
    }

    /**
     * Test if an empty configuration set with missing video manager Id throws an exception.
     *
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEmptyConfigThrowsExceptionUnlessVmId()
    {
        $config = $this->getEmptyConfig();
        unset($config['video_collection']['collections']['collection_name']['vm_id']);
        $videoCollectionExtension = new VideoCollectionExtension();
        $container = new ContainerBuilder();

        $videoCollectionExtension->load($config, $container);
    }

    /**
     * Test if an empty configuration set will be filled with default values.
     */
    public function testEmptyConfigDefaults()
    {
        $config = $this->getEmptyConfig();
        $videoCollectionExtension = new VideoCollectionExtension();
        $container = new ContainerBuilder();

        $videoCollectionExtension->load($config, $container);

        $videoCollections = $container->getParameter('video_collections');

        $expected = [
            'collection_name' => [
                'vm_id' => 456,
                'data_provider' => 'vmpro',
                'limit' => 12,
                'filter' => [],
            ],
        ];

        $this->assertEquals($expected, $videoCollections);
    }
}
