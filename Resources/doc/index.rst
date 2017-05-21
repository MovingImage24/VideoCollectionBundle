Getting started with the Video Collection Bundle
================================================

This Symfony bundle allows you to abstract away the need to interact directly with the Video
Manager APIs, and enable you to define criteria as to what forms 'collections' of videos, and
offers an elegant way to incorporate this into the Symfony Framework.

Prerequisites
-------------

This bundle requires version 2.7+ or 3.0+ of the Symfony framework, and supports
PHP 5.6, 7.0 and 7.1

Installation
------------

Installing this bundle is a quick and simple process that should only take a minute.

Install library with composer
_____________________________

First you have to install it using Composer:

.. code-block:: bash

    $ composer require movingimage/video-collection-bundle

Enable bundle
_____________

Enable the bundle in your kernel:

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new MovingImage\Bundle\VideoCollection\VideoCollectionBundle(),
            );

            // ...
        }

        // ...
    }

Getting Started
_______________

After successful installation, let's set up our first video collection.

This guide assumes you're using the `Video Manager Pro data provider <https://github.com/MovingImage24/VMProDataProviderBundle>`_,
which isn't automatically installed but assumed to be the default data provider if you
don't explicitly configure otherwise.

Let's type a simple collection:

.. code-block:: yaml

    # app/config/configuration.yml

    video_collection:
        collections:
            video_list:
                limit: 10
                channel_id: 10110

Now inside your controller, you can access this collection as a service:

.. code-block:: php

    <?php

    // AppBundle/Controller/IndexController.php

    class IndexController extends Controller
    {
        // ...
        private function indexAction(Request $request)
        {
            $collection = $this->get('video_collections.collections.video_list');

            foreach ($collection->generator() as $video) {
                echo $video->getTitle() . PHP_EOL;
            }
        }
    }

Each defined collection is accessible through the service container, so you may retrieve it inside container
aware services such as Controllers or Commands, or inject it into your other services through service definition:

.. code-block:: yaml

    # app/config/services.yml

    services:
        app.my_service:
            class: AppBundle\MyService
            parameters: ['@video_collections.collections.video_list']

One final note in this 'Getting Started' is that each parameter inside the video collection definition can be
expressed in a way that you can inject the eventual value during run-time:

.. code-block:: yaml

    # app/config/configuration.yml

    video_collection:
        collections:
            video_list:
                limit: 10
                channel_id: ~

By defining specific parameters as ``~`` (which represents NULL in YAML), you can set them at runtime:

.. code-block:: php

    <?php

    // AppBundle/Controller/IndexController.php

    class IndexController extends Controller
    {
        // ...
        private function indexAction(Request $request)
        {
            $collection = $this->get('video_collections.collections.video_list');
            $collection->setOption('channel_id', $request->get('channel_id');

            // ...
        }
    }


Next Steps
__________

For a more complete guide on configuration, please take a look at `How To Configure <configuration.rst>`_
video collections, which shows the full range of functionalities and how to apply them.

Services
________

If you want to read about any functionality in particular, you can read about the following services:

* `Collection <services/collection.rst>`_ - Documentation for the Collection base class
* `CollectionRegistry <services/collection_registry.rst>`_ - Documentation for the collection registry helper