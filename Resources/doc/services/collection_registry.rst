Collection Registry & Collection Tags
=====================================

The Collection Registry is a special service that allows you to simplify the code you use to
retrieve multiple collections of the same semantic hierarchy at the same time.

Simple collections
------------------

For example, imagine you have a lot of collections:

.. code-block:: yaml

    video_collection:
        default:
            vm_id: 123
            limit: 10
        collections:
            channel_1:
                channel_id: 13264
            channel_2:
                channel_id: 13265
            channel_3:
                channel_id: 13266
            channel_4:
                channel_id: 13267

But you want all of these to appear on the same page below each other, so like:

+---------------+-------------+
| Collection #1 | Videos      |
+---------------+-------------+
| Collection #2 | Videos      |
+---------------+-------------+
| Collection #3 | Videos      |
+---------------+-------------+
| Collection #4 | Videos      |
+---------------+-------------+

Your implementation code would look something like this:

.. code-block:: php

    <?php

    // ...

    $collections = [
        $container->get('video_collections.collection.channel_1'),
        $container->get('video_collections.collection.channel_2'),
        $container->get('video_collections.collection.channel_3'),
        $container->get('video_collections.collection.channel_4'),
    ];

    foreach ($collections as $collection)  {
        // Draw the table
    }

This is bad, because effectively you statically name all of your collections twice, and if you were
to rename them in the configuration, you would have to rename them in the code as well.

Using the service ``@video_collections.registry`` you can simplify the example above to this:

.. code-block:: php

    <?php

    // ...

    $registry = $container->get('video_collections.registry');

    foreach ($registry->getAll() as $collection)  {
        // Draw the table
    }

Tagged Collections
------------------

Now imagine your business logic is a little bit more complicated and you want the following:

But you want all of these to appear on the same page below each other, so like:

+---------------+-------------+
| Featured #1   | Videos      |
+---------------+-------------+
| Featured #2   | Videos      |
+---------------+-------------+
| Collection #1 | Videos      |
+---------------+-------------+
| Collection #2 | Videos      |
+---------------+-------------+
| Collection #3 | Videos      |
+---------------+-------------+
| Collection #4 | Videos      |
+---------------+-------------+

You have two collections of featured videos that you want displayed at the top, and an undefined
amount of collections below that. You can use **collection tags** for this:

.. code-block:: yaml

    video_collection:
        default:
            vm_id: 123
            limit: 10
        collections:
            featured_1:
                channel_id: 13264
                search_term: true
                search_field: featured
                tags: [featured]
            featured_2:
                channel_id: 13264
                search_term: true
                search_field: featured
                tags: [featured]
            channel_1:
                channel_id: 13264
                tags: [other]
            channel_2:
                channel_id: 13265
                tags: [other]
            channel_3:
                channel_id: 13266
                tags: [other]
            channel_4:
                channel_id: 13267
                tags: [other]

Using the ``tags`` parameter in the collection definition, you can group video collections and in your
code retrieve them by tag:

.. code-block:: php

    <?php

    // ...

    $registry = $container->get('video_collections.registry');

    foreach ($registry->getByTag('featured') as $collection)  {
        // Draw the featured videos at the top of the table
    }

    foreach ($registry->getByTag('other') as $collection)  {
        // Draw the featured videos at the top of the table
    }

Each collection can have multiple tags, so you can make it as flexible as you'd like.