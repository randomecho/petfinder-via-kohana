# Petfinder via Kohana

Use this module to easily connect a Kohana 3.2 powered website with the Petfinder API and display pet or shelter information as needed from its database.

Initially developed for use at [KentuckyAnimalRescue.org](http://kentuckyanimalrescue.org/ "Little Hills of Kentucky Animal Rescue, Inc.")

## Install

To add this to your site, you'll need to adjust your bootstrap.php file accordingly.

### Kohana::Modules

Enable the module by adding it to the `Kohana::modules` array. Name it as you please, but the path should match its location.

    Kohana::modules(array(
        'petfinder'       => MODPATH.'petfinder',  // Petfinder API
    ));


### Route::set

Here is an example route you can drop into the `Route::set` rules

    Route::set('petfinder', 'petfinder(/<action>(/<id>))')
        ->defaults(array(
            'controller' => 'petfinder',
            'action'     => 'index',
    ));

## Calling the API

There are many method calls you can issue to the API. In this showing, we're calling the method of the module that will pluck a pet's details at random:

    Petfinder::random();

The initial result will be an object data of the pet's information in either XML or JSON format. What's ultimately sent back after the module has its way will be an homogenised array for easier handling in the controller and/or view.

More examples on how to call up the Petfinder module can be found in the **application/classes/controller/petfinder.php** file.

## Configuration

With your API key and secret, head on over to **modules/petfinder/config/petfinder.php** and drop them in.

You can select either XML or JSON format for the Petfinder API return results, but as the module will render the data in a no fuss, single format whatever you choose, it doesn't really matter.

## API key

For a developer API key, go to [petfinder.com/developers/api-key](http://www.petfinder.com/developers/api-key)

### Petfinder API documentation

To get a breakdown of the GET methods and parameters used to connect with the Petfinder API, you can read the documentation found at [petfinder.com/developers/api-docs](http://www.petfinder.com/developers/api-docs)