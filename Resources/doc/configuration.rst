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

**Default value:** `vmpro`

This parameter is used to specify which data provider is to be used to retrieve the actual videos for the collections.



Default values
--------------

Collection Tags & Registry
--------------------------

