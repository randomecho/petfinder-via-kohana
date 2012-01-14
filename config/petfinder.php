<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'api_key' => 'YOUR_API_KEY',
	'api_secret' => 'YOUR_API_SECRET',
	'shelter_id' => 'YOUR_SHELTER_ID',

	'api_url' => 'http://api.petfinder.com/',
	'url_route' => 'petfinder',

	'get_token' => 'auth.getToken',
	'get_breeds' => 'breed.list',
	'get_pet' => 'pet.get',
	'get_random' => 'pet.getRandom',
	'find_pet' => 'pet.find',
	'find_shelter' => 'shelter.find',
	'get_shelter' => 'shelter.get',
	'get_shelterpets' => 'shelter.getPets',
	'get_shelterbreeds' => 'shelter.listByBreed',
);