# Petfinder via Kohana

Petfinder is an online, searchable database of animals who need homes.
This module allows an animal shelter to display their pets listed on
the Petfinder database through their own website (built on Kohana 3.2).

Initially developed for use at http://kentuckyanimalrescue.org/

### bootstrap.php

    Route::set('petfinder', Kohana::$config->load('petfinder.url_route').'(/<action>(/<id>))')
        ->defaults(array(
            'controller' => 'petfinder',
            'action'     => 'index',
    ));

## API key

For a developer API key, go to [petfinder.com/developers/api-key](http://www.petfinder.com/developers/api-key)

### Petfinder API documentation

To get a breakdown of the GET methods and parameters used to connect with the Petfinder API,
you can read the documentation found at: [petfinder.com/developers/api-docs](http://www.petfinder.com/developers/api-docs)