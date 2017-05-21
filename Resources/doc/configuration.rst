Configuration
=============

Configuration basics
--------------------

Like described in the 'Getting Started' guide, video collections are configuration definitions:

.. code-block:: yaml

    # app/config/configuration.yml

    video_collection:
        collections:
            video_list:
                limit: 10
                channel_id: 10110

Where each node in the `collection` configuration node becomes available as a service, in this case:

* **@video_collections.collection.video_list**

Which can either be accessed through container aware services:

.. code-block:: php

    <?php

    // AppBundle/Controller/IndexController.php

    class IndexController extends Controller
    {
        // ...
        private function indexAction(Request $request)
        {
            $collection = $this->get('video_collections.collections.video_list');

            // ...
        }
    }

Or in service definition:

.. code-block:: yaml

    # app/config/services.yml

    services:
        app.my_service:
            class: AppBundle\MyService
            parameters: ['@video_collections.collections.video_list']

Parameters
----------

There's quite a few parameters available to specifically target your collections. Here's what they are and how to use them.

data_provider
_____________

**Default value:** ``vmpro``

This parameter is used to specify which data provider is to be used to retrieve the actual videos for the collections.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                data_provider: vm6
                # ...

vm_id
_____

**Default value:** ``null``

This parameter defines which Video Manager ID the request to retrieve the videos will be made to. This parameter is
only relevant when you use the Video Manager Pro data provider.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                vm_id: 24324
                # ...

channel_id
__________

**Default value:** ``null``

This parameter defines which channel the result set will be limited to.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                channel_id: 123123
                # ...

limit
_____

**Default value:** ``null``

This parameter defines how many results to retrieve within your collection. This is useful when you don't need
many videos or are using pagination to limit the amount of videos per page.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                limit: 10
                # ...

offset
______

**Default value:** ``0``

This parameter defines with what kind of offset it will retrieve data from the video manager. Useful in combination
with ``limit`` for use with pagination for example.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                # Display results 10-20
                limit: 10
                offset: 10
                # ...

search_term
___________

**Default value:** ``null``

This parameter can be used to define a search term that any videos that will end up in your collection
will have to match.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                search_term: "Hello"
                # ...

search_field
____________

**Default value:** ``null``

This parameter can be used to define which field in the video a search term that any videos
that will end up in your collection will have to match. Used in conjunction with ``search_term``,
will do nothing otherwise.

Please consult the video manager API documentation for which fields you can search in for which
ever video manager you're using.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                search_term: "Hello"
                search_field: title
                # ...

order_by
________

**Default value:** ``null``

This parameter lets you define which field in the video manager the results inside your collection
will be sorted by.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                order_by: date_created
                # ...

order
_____

**Default value:** ``null``
**Possible values:** ``asc`` or ``desc``

Used in conjunction with ``order_by``, this parameter lets you define the actual sort order.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                order_by: date_created
                order: desc # Newest videos first
                # ...

Video ID
________

**Default value:** ``null``

**This parameter is only relevant when you use ``Collection::getOne()``**

This parameter lets you define the specific video ID you want to fill your collection with.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                id: asdlkkljASLJK
                # ...

player_id
_________

**Default value:** ``null``

**This parameter is only relevant when you use ``Collection::getOne()``**

This parameter lets you define which embed code can be accessed, and will use the video manager's player definition ID
for retrieving the embed code.

Example:

.. code-block:: yaml

    video_collection:
        collections:
            my_collection:
                player_id: ASDlkAklsjdf
                # ...


Default values
--------------

Sometimes when you have a lot of collections, your configuration will be very long and not very compact.

Luckily, there's some smart syntax that allows you to compact your configuration significantly.

Imagine you have these collections:

.. code-block:: yaml

    video_collection:
        collections:
            channel_1:
                vm_id: 123
                channel_id: 13264
                limit: 10
            channel_2:
                vm_id: 123
                channel_id: 13265
                limit: 10
            channel_3:
                vm_id: 123
                channel_id: 13266
                limit: 10
            channel_4:
                vm_id: 123
                channel_id: 13267
                limit: 10

You can express this in a different way and get the same result, using the `default` option:

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

The following parameters are available to be defined as ``default`` at this time:

* **vm_id**
* **limit**
* **player_id**

If you need any new ones, please open a PR or a ticket requesting for new parameters to be added here.

Collection Tags & Registry
--------------------------

