<?php

namespace MovingImage\Bundle\VideoCollection\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use MovingImage\Bundle\VideoCollection\DependencyInjection\Compiler\CollectionServiceDefinitionPass;
use MovingImage\Bundle\VideoCollection\Factory\ServiceDefinitionFactory;
use Symfony\Component\DependencyInjection\Definition;

class CollectionServiceDefinitionPassTest extends \PHPUnit_Framework_TestCase
{
    public function testServices()
    {
        $videoCollections = [
            'collection_name_1' => [
                'channel_id' => 123,
                'vm_id' => 456
            ]
            ,'collection_name_2' => [
                'channel_id' => 123,
                'vm_id' => 456
            ],
            'collection_name_3' => [
                'channel_id' => 123,
                'vm_id' => 456
            ]
        ];

        $videoCollectionsCount = count($videoCollections);

        $definitionFactory = $this->createMock(ServiceDefinitionFactory::class);
        $definitionFactory
            ->expects($this->exactly($videoCollectionsCount))
            ->method('createCollectionDefinition')
            ->willReturn(new Definition());

        $definitionFactory
            ->expects($this->exactly($videoCollectionsCount))
            ->method('getPrefix')
            ->willReturn('video_collections');

        $container = $this->createMock(ContainerBuilder::class);

        $container
            ->expects($this->once())
            ->method('get')
            ->with('video_collections.service_definition_factory')
            ->willReturn($definitionFactory);

        $container
            ->expects($this->once())
            ->method('getParameter')
            ->with('video_collections')
            ->willReturn($videoCollections);

        $container
            ->expects($this->exactly($videoCollectionsCount))
            ->method('setDefinition')
            ->will($this->onConsecutiveCalls(
                $this->returnCallback(function($string) {
                    $this->assertEquals('video_collections.collections.collection_name_1', $string);
                }),
                $this->returnCallback(function($string) {
                    $this->assertEquals('video_collections.collections.collection_name_2', $string);
                }),
                $this->returnCallback(function($string) {
                    $this->assertEquals('video_collections.collections.collection_name_3', $string);
                })
            ));

        $compiler = new CollectionServiceDefinitionPass();

        $compiler->process($container);
    }
}
