<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Petfinder listing and display
 *
 * @package    Petfinder via Kohana
 * @category   Core
 * @author     Soon Van - randomecho.com
 * @copyright  2012 Soon Van
 * @license    http://www.opensource.org/licenses/BSD-3-Clause
 */

class Controller_Petfinder extends Controller_TemplateIndex {

	/**
	 * Request the list of adoptable pets from Petfinder via their RESTful API.
	 * If a list is available, render the collection of pets found.
	 * Otherwise show the view saying there are none available.
	 *
	 */
	public function action_index()
	{
		$adoptable = simplexml_load_file(Kohana::$config->load('petfinder.api_url').
								Kohana::$config->load('petfinder.get_shelterpets').
								'?key='.Kohana::$config->load('petfinder.api_key').
								'&id='.Kohana::$config->load('petfinder.shelter_id'));

		if ($adoptable->header->status->code == 100 AND count( (array) $adoptable->pets) > 0)
		{
			$shelter_pets = (array) $adoptable->pets;

			$shelter_pets['legend'] = Kohana::message('petfinder');
			$shelter_pets['url_details'] = Kohana::$config->load('petfinder.url_route').'/details/';

			$this->template->content = View::factory('petfinder_multi', $shelter_pets);
		}
		else
		{
			$info['url_main'] = Kohana::$config->load('petfinder.url_route');
			$info['error_message'] = Kohana::message('petfinder', 'error.none');
			$info['error_heading'] = Kohana::message('petfinder', 'error.none_heading');

			$this->template->content = View::factory('petfinder_none', $info);
		}

		$this->template->title = Kohana::message('petfinder', 'meta.title').' - '.$this->template->title;
	}

	/**
	 * Display the details of an individual pet as requested from the list
	 * Reject showing if it doesn't contain the pet name in URL and bounce back to main list
	 * If the URL is correct, but pet ID is invalid, display the error message view
	 *
	 */
	public function action_details()
	{
		$id_pet = $this->request->param('id');

		// reject as invalid if not parsed by the site with nice url featuring pet name
		if ( ! stristr($id_pet, '-'))
			Request::current()->redirect($this->template->baseurl.Kohana::$config->load('petfinder.url_route'));

		$url_pet = explode('-', $id_pet);
		$id_pet = (int) $url_pet[0];

		$pet_details = simplexml_load_file(Kohana::$config->load('petfinder.api_url').
								Kohana::$config->load('petfinder.get_pet').
								'?key='.Kohana::$config->load('petfinder.api_key').
								'&id='.$id_pet);

		if ($pet_details->header->status->code == 100)
		{
			$shelter_pets['pet'] = $pet_details->pet;
			$shelter_pets['status_heading'] = Kohana::message('petfinder', 'status_heading.'.$pet_details->pet->status);
			$shelter_pets['legend_sex'] = Kohana::message('petfinder', 'legend_sex.'.$pet_details->pet->sex);

			foreach ($pet_details->pet->options->option as $option)
			{
				$shelter_pets['options'][] = Kohana::message('petfinder', 'legend_options.'.$option);
			}

			$shelter_pets['url_main'] = Kohana::$config->load('petfinder.url_route');

			$this->template->content = View::factory('petfinder_single', $shelter_pets);
			$this->template->title = $pet_details->pet->name.$shelter_pets['status_heading'].' - '.$this->template->title;
		}
		else
		{
			$info['url_main'] = Kohana::$config->load('petfinder.url_route');
			$info['error_message'] = Kohana::message('petfinder', 'error.invalid');
			$info['error_heading'] = Kohana::message('petfinder', 'error.invalid_heading');

			$this->template->content = View::factory('petfinder_none', $info);
			$this->template->title = Kohana::message('petfinder', 'meta.invalid').' - '.$this->template->title;
		}

	}

}