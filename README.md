# Petfinder via Kohana

Petfinder is an online, searchable database of animals who need homes. 
This module allows an animal shelter to display their pets listed on 
the Petfinder database through their own website (built on Kohana 3.2).

Initially developed for use at http://kentuckyanimalrescue.org/

## URL path

The following is the default path used to list the current pets posted by the shelter:

    http://example.com/petfinder
    
This can be configured to whatever else you would like as the bootstrap file will pick up 
the URL path from the config file.

Individual pet listings, with extra information and details, will be found at the default 
sample URL:

    http://example.com/petfinder/details/21249157-petname


### bootstrap.php	

    Route::set('petfinder', Kohana::$config->load('petfinder.url_route').'(/<action>(/<id>))')
        ->defaults(array(
            'controller' => 'petfinder',
            'action'     => 'index',
    ));


## API key

For a developer API key, go to http://www.petfinder.com/developers/api-key


