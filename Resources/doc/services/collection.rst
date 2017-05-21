Collection
==========

This page explains which methods are available on instances of Collection, and how to use them.

getName()
---------

Get the service name of this individual collection

getOptions()
------------

Retrieve all options from this collection.

Imagine your collection is like this:

.. code-block:: yaml

    video_collection:
        collections:
            video_list:
                channel_id: 10110

You'd get an array like this:

.. code-block:: json
    [
        'channel_id' => 10110
    ]


getOption()
-----------

Retrieve a single option from this collection. This method will throw an exception
if the option you're trying to retrieve is not statically defined in the collection definition.

setOption()
-----------

Set the value of an option in this collection. This method will throw an exception
if the option you're trying to retrieve is not statically defined in the collection definition.

generator()
-----------

Lazily get all of the collection's videos. This is useful in scenarios where you use generators
yourself to do processing one-by-one.

Examples:

.. code-block:: php

    <?php
    // ...

    foreach ($collection->generator() as $video) {
        echo $video->getId() . PHP_EOL;
    }

Something to keep in mind is that if you use array functions to retrieve nodes from the generator,
they will disappear from the generator when you iterate it at a later time:

.. code-block:: php

    <?php
    // ...

    // Imagine there is 10 videos in the collection at first
    $video = reset($collection->generator());

    // Now there's only 9 videos left that it will iterate through
    foreach ($collection->generator() as $video) {
        echo $video->getId() . PHP_EOL;
    }

getOne()
--------

Retrieve a single video from a collection, wrapped inside a wrapper object with both the video entity
and the embed code for embedding it.

If you use the ``id`` parameter, it will fetch video by ID. If you don't, it will fetch the first video
from your collection without affecting the collection's generator, so you may still iterate through it in whole.

**Parameter player_id is REQUIRED for this functionality**

Example:

.. code-block:: yaml

    video_collection:
        collections:
            play_video:
                id: 13266
                player_id: 92ijdlkFL

.. code-block:: php

    <?php

    $videoWrapper = $collection->getOne();

    // Video object
    $video = $videoWrapper->getVideo();
    echo $video->getId();

    // EmbedCode object
    $embedCode = $videoWrapper->getEmbedCode();
    echo $embedCode->getCode() // Embed the video on the page



getAll()
--------

Same as ``generator()`` except that rather than lazy generator expression iterator, it will each time you call
it make new API requests and return the full result as an array. If you can use ``generator()``, go for that.

getCount()
----------

Get the total count of videos inside this collection. This ignores the ``limit`` parameter.
