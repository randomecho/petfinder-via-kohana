<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Petfinder via Kohana
 *
 * Class wrapper for the Petfinder.com API using Kohana 3.2
 *
 * @package    Petfinder
 * @author     Soon Van - randomecho.com
 * @copyright  2012 Soon Van
 * @license    http://www.opensource.org/licenses/BSD-3-Clause
 * @version    2.0
 */

class Petfinder {

	protected static $endpoint = 'http://api.petfinder.com/';

	/**
	 * Formats the URI to ping the API resource and sends off to parse
	 *
	 *     // Request the pet.get resource with a pet ID of FOO
	 *     $response = Petfinder::connect('pet.get', '&id=FOO');
	 *
	 * @param   string  API method
	 * @param   string  arguments
	 * @return  object
	 * @uses    Kohana::$config
	 * @uses    Petfinder::$endpoint
	 * @uses    Petfinder::parse
	 * @uses    Request::factory
	 * @throws  Kohana_Exception
	 */
	public static function connect($method = 'random', $output = '')
	{
		$api_key = Kohana::$config->load('petfinder.api_key');
		$api_secret = Kohana::$config->load('petfinder.api_secret');
		$api_format = Kohana::$config->load('petfinder.format');

		// Only need the API key to connect with at this time
		if ($api_key == '')
		{
			throw new Kohana_Exception('API credentials are required. Please check the Petfinder configuration file.');
		}

		// Arguments resource is going to need, check http://www.petfinder.com/developers/api-docs
		$api_params = (trim($output) != '') ? $output : '' ;

		// Bring altogether to form our complete URI
		$api_connect = Petfinder::$endpoint.$method.'?format='.$api_format.'&key='.$api_key.$api_params;

		// Call the resource and cast it into an array to deal with
		$request = Request::factory($api_connect)->execute();
		$response = Petfinder::parse($request);

		return $response;
	}

	/**
	 * Parse the resource sent from the API and try to render it in a format to better deal with.
	 * Casts the result from either XML or JSON format into an array
	 *
	 * @param   object  resource as return by API call
	 * @return  array
	 * @uses    Kohana::$config
	 * @uses    Petfinder::parse
	 * @throws  Kohana_Exception
	 */
	public static function parse($request)
	{
		$api_format = Kohana::$config->load('petfinder.format');

		if (Kohana::$config->load('petfinder.format') == 'xml')
		{
			$response = simplexml_load_string($request, NULL, LIBXML_NOCDATA);
			$parsed = $response;
		}
		else
		{
			$response = json_decode($request);
			$parsed = $response->petfinder;
		}

		if ( ($api_format == 'xml' AND $parsed->header->status->code != 100) OR ($api_format == 'json' AND $parsed->header->status->code->{'$t'} != 100) )
		{
			$error = ($api_format == 'xml') ? $parsed->header->status->message : $parsed->header->status->message->{'$t'};

			throw new Kohana_Exception('Invalid request made to Petfinder API. Error: '.$error);
		}

		return (array) $parsed;
	}

	/**
	 * Return the full details of single pet specified by its Petfinder ID
	 *
	 * @param   string   Petfinder pet ID
	 * @return  array
	 * @uses    Petfinder::connect
	 * @uses    Petfinder::pet_vars
	 * @throws  Kohana_Exception
	 */
	public static function single($pet_id)
	{
		if (trim($pet_id) == '')
		{
			throw new Kohana_Exception('Please specify a Petfinder ID');
		}

		$response = Petfinder::connect('pet.get', '&id='.$pet_id);

		$pet_details = Petfinder::pet_vars($response['pet']);

		return $pet_details;
	}

	/**
	 * Re-save the values of a pet sent back as plain array, making less format type checking
	 * work for controllers/views when dealing against the XML or JSON format returned.
	 *
	 * @param   object   values from resource in either an XML or JSON formatted object
	 * @return  array
	 * @uses    Kohana::$config
	 */
	protected static function pet_vars($pet_info)
	{
		$format_type = Kohana::$config->load('petfinder.format');

		if ($format_type == 'xml')
		{
			$pet_details['age'] = (isset($pet_info->age)) ? $pet_info->age : '';
			$pet_details['animal'] = (isset($pet_info->animal)) ? $pet_info->animal : '';
			$pet_details['description'] = (isset($pet_info->description)) ? $pet_info->description : '';
			$pet_details['id'] = (isset($pet_info->id)) ? $pet_info->id : '';
			$pet_details['lastUpdate'] = (isset($pet_info->lastUpdate)) ? $pet_info->lastUpdate : '';
			$pet_details['mix'] = (isset($pet_info->mix)) ? $pet_info->mix : '';
			$pet_details['name'] = (isset($pet_info->name)) ? $pet_info->name : '';
			$pet_details['sex'] = (isset($pet_info->sex)) ? $pet_info->sex : '';
			$pet_details['shelterId'] = (isset($pet_info->shelterId)) ? $pet_info->shelterId : '';
			$pet_details['size'] = (isset($pet_info->size)) ? $pet_info->size : '';
			$pet_details['status'] = (isset($pet_info->status)) ? $pet_info->status : '';

			$pet_details['contact_name'] = (isset($pet_info->contact->name)) ? $pet_info->contact->name : '';
			$pet_details['contact_email'] = (isset($pet_info->contact->email)) ? $pet_info->contact->email : '';
			$pet_details['contact_phone'] = (isset($pet_info->contact->phone)) ? $pet_info->contact->phone : '';
			$pet_details['contact_fax'] = (isset($pet_info->contact->fax)) ? $pet_info->contact->fax : '';
			$pet_details['contact_address1'] = (isset($pet_info->contact->address1)) ? $pet_info->contact->address1 : '';
			$pet_details['contact_address2'] = (isset($pet_info->contact->address2)) ? $pet_info->contact->address2 : '';
			$pet_details['contact_city'] = (isset($pet_info->contact->city)) ? $pet_info->contact->city : '';
			$pet_details['contact_state'] = (isset($pet_info->contact->state)) ? $pet_info->contact->state : '';
			$pet_details['contact_zip'] = (isset($pet_info->contact->zip)) ? $pet_info->contact->zip : '';

			if (isset($pet_info->breeds->breed))
			{
				$pet_details['breed'] = (string) $pet_info->breeds->breed;
			}
			else
			{
				foreach ($pet_info->breeds->breed as $breed_id => $breed_type)
				{
					$pet_details['breed'][] = $breed_type;
				}
			}

			if (isset($pet_info->options->option))
			{
				$pet_details['options'][] = (string) $pet_info->options->option;
			}
			elseif (isset($pet_info->options->option))
			{
				foreach ($pet_info->options->option as $option_id => $option_info)
				{
					$pet_details['options'][] = $option_info;
				}
			}
			else
			{
				$pet_details['options'] = array();
			}

			if (isset($pet_info->media->photos->photo))
			{
				foreach ($pet_info->media->photos->photo as $photo)
				{
					$pet_details['media'][ (string) $photo->attributes()->id][ (string) $photo->attributes()->size] = $photo;
				}
			}
			else
			{
				$pet_details['media'] = array();
			}
		}
		else
		{
			$pet_details['age'] = (isset($pet_info->age->{'$t'})) ? $pet_info->age->{'$t'} : '';
			$pet_details['animal'] = (isset($pet_info->animal->{'$t'})) ? $pet_info->animal->{'$t'} : '';
			$pet_details['description'] = (isset($pet_info->description->{'$t'})) ? $pet_info->description->{'$t'} : '';
			$pet_details['id'] = (isset($pet_info->id->{'$t'})) ? $pet_info->id->{'$t'} : '';
			$pet_details['lastUpdate'] = (isset($pet_info->lastUpdate->{'$t'})) ? $pet_info->lastUpdate->{'$t'} : '';
			$pet_details['mix'] = (isset($pet_info->mix->{'$t'})) ? $pet_info->mix->{'$t'} : '';
			$pet_details['name'] = (isset($pet_info->name->{'$t'})) ? $pet_info->name->{'$t'} : '';
			$pet_details['sex'] = (isset($pet_info->sex->{'$t'})) ? $pet_info->sex->{'$t'} : '';
			$pet_details['shelterId'] = (isset($pet_info->shelterId->{'$t'})) ? $pet_info->shelterId->{'$t'} : '';
			$pet_details['size'] = (isset($pet_info->size->{'$t'})) ? $pet_info->size->{'$t'} : '';
			$pet_details['status'] = (isset($pet_info->status->{'$t'})) ? $pet_info->status->{'$t'} : '';

			$pet_details['contact_name'] = (isset($pet_info->contact->name->{'$t'})) ? $pet_info->contact->name->{'$t'} : '';
			$pet_details['contact_email'] = (isset($pet_info->contact->email->{'$t'})) ? $pet_info->contact->email->{'$t'} : '';
			$pet_details['contact_phone'] = (isset($pet_info->contact->phone->{'$t'})) ? $pet_info->contact->phone->{'$t'} : '';
			$pet_details['contact_fax'] = (isset($pet_info->contact->fax->{'$t'})) ? $pet_info->contact->fax->{'$t'} : '';
			$pet_details['contact_address1'] = (isset($pet_info->contact->address1->{'$t'})) ? $pet_info->contact->address1->{'$t'} : '';
			$pet_details['contact_address2'] = (isset($pet_info->contact->address2->{'$t'})) ? $pet_info->contact->address2->{'$t'} : '';
			$pet_details['contact_city'] = (isset($pet_info->contact->city->{'$t'})) ? $pet_info->contact->city->{'$t'} : '';
			$pet_details['contact_state'] = (isset($pet_info->contact->state->{'$t'})) ? $pet_info->contact->state->{'$t'} : '';
			$pet_details['contact_zip'] = (isset($pet_info->contact->zip->{'$t'})) ? $pet_info->contact->zip->{'$t'} : '';

			if (isset($pet_info->breeds->breed->{'$t'}))
			{
				$pet_details['breed'] = $pet_info->breeds->breed->{'$t'};
			}
			else
			{
				foreach ($pet_info->breeds->breed as $breed_id => $breed_type)
				{
					$pet_details['breed'][] = $breed_type->{'$t'};
				}
			}

			if (isset($pet_info->options->option->{'$t'}))
			{
				$pet_details['options'][] = $pet_info->options->option->{'$t'};
			}
			elseif (isset($pet_info->options->option))
			{
				foreach ($pet_info->options->option as $option_id => $option_info)
				{
					$pet_details['options'][] = $option_info->{'$t'};
				}
			}
			else
			{
				$pet_details['options'] = array();
			}

			if (isset($pet_info->media->photos->photo))
			{
				foreach ($pet_info->media->photos->photo as $photo)
				{
					$pet_details['media'][$photo->{'@id'}][$photo->{'@size'}] = $photo->{'$t'};
				}
			}
			else
			{
				$pet_details['media'] = array();
			}
		}

		return $pet_details;
	}

}
