<?php

namespace MovingImage\Bundle\VideoCollection\Registry;

use MovingImage\VideoCollection\Entity\Collection;

/**
 * Class VideoCollectionRegistry.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoCollectionRegistry
{
    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var array[Collection[]]
     */
    private $tags;

    /**
     * @return Collection[]
     */
    public function getAll()
    {
        return $this->collections;
    }

    /**
     * @param string $tag
     *
     * @return Collection[]
     */
    public function getByTag($tag)
    {
        return isset($this->tags[$tag])
            ? $this->tags[$tag]
            : [];
    }

    /**
     * Add a reference to a collection to the registry object, and
     * optionally store references by collection tags for assorted
     * retrieval.
     *
     * @param Collection $collection
     * @param array      $attributes
     */
    public function addCollection(Collection $collection, $attributes)
    {
        $this->collections[] = $collection;

        foreach ($attributes as $attribute) {
            if (isset($attribute['collection_tag'])) {
                if (!isset($this->tags[$attribute['collection_tag']])) {
                    $this->tags = [];
                }

                $this->tags[$attribute['collection_tag']] = $collection;
            }
        }
    }
}
