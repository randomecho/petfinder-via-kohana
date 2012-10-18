<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Petfinder via Kohana - Examples
 *
 * Basic controller that showcases calls to the Petfinder API module
 *
 * In this example, we are extending a template controller called Controller_TemplateIndex
 * that catches and displays its content in the $this->template->content variable.
 *
 * @package    Petfinder
 * @author     Soon Van - randomecho.com
 * @copyright  2012 Soon Van
 * @license    http://www.opensource.org/licenses/BSD-3-Clause
 * @version    2.0
 */

class Controller_Petfinder extends Controller_TemplateIndex {

	public function action_index()
	{
		$this->template->content = View::factory('petfinder_intro');
	}

	/**
	 *   Example URL call:
	 *   pet/17691749
	 *
	 */
	public function action_pet()
	{
		$pet_id = $this->request->param('id');

		$result['info'] = Petfinder::single($pet_id);

		$this->template->content = View::factory('petfinder_single', $result);
	}

	/**
	 *   Example URL call:
	 *   random
	 *   random?animal=bird
	 *   random?animal=pig&sex=M
	 *
	 */
	public function action_random()
	{
		$filter = $this->request->query();

		$result['info'] = Petfinder::random($filter);

		$this->template->content = View::factory('petfinder_single', $result);
	}

	/**
	 *   Example URL call:
	 *   search?location=40509
	 *   search?location=10016&animal=reptile
	 *
	 */
	public function action_search()
	{
		$filter = $this->request->query();
		$pet_profiles = '';

		$found = Petfinder::search_pets($filter);

		foreach ($found['results'] as $pet_details)
		{
			$pet_info['info'] = $pet_details;
			$pet_profiles .= View::factory('petfinder_single', $pet_info);
		}

		$result['info'] = $pet_profiles;

		$this->template->content = View::factory('petfinder_info', $result);
	}

	/**
	 *   Example URL call:
	 *   breeds/horse
	 *   breeds/dog
	 *
	 */
	public function action_breeds()
	{
		$animal = $this->request->param('id');

		$result['info'] = Petfinder::breeds($animal);

		$this->template->content = View::factory('petfinder_list_breeds', $result);
	}

	/**
	 *   Example URL call:
	 *   shelter/KY361
	 *
	 */
	public function action_shelter()
	{
		$filter = $this->request->query();
		$shelter_id = $this->request->param('id');

		if (is_null($shelter_id))
		{
			$found = Petfinder::search_shelters($filter);
			$profiles = '';

			foreach ($found['results'] as $details)
			{
				$profile_info['info'] = $details;
				$profiles .= View::factory('petfinder_shelter', $profile_info);
			}

			$result['info'] = $profiles;
		}
		else
		{
			$found['info'] = Petfinder::shelter($shelter_id);

			$result['info'] = View::factory('petfinder_shelter', $found);
		}

		$this->template->content = View::factory('petfinder_info', $result);
	}

	/**
	 *   Example URL call:
	 *   shelterpets/KY361
	 *   shelterpets/CA154?animal=dog
	 *
	 */
	public function action_shelterpets()
	{
		$shelter_id = $this->request->param('id');
		$filter = $this->request->query();
		$pet_profiles = '';

		$found = Petfinder::shelter_pets($shelter_id, $filter);

		foreach ($found['results'] as $pet_details)
		{
			$pet_info['info'] = $pet_details;
			$pet_profiles .= View::factory('petfinder_single', $pet_info);
		}

		$result['info'] = $pet_profiles;

		$this->template->content = View::factory('petfinder_info', $result);
	}


	/**
	 *   Example URL call:
	 *   shelterbreeds?animal=dog&breed=basenji
	 *   shelterbreeds?animal=cat&breed=silver
	 *
	 */
	public function action_shelterbreeds()
	{
		$filter = $this->request->query();
		$shelter_profiles = '';

		$found = Petfinder::shelter_breeds($filter);

		foreach ($found['results'] as $details)
		{
			$shelter['info'] = $details;
			$shelter_profiles .= View::factory('petfinder_shelter', $shelter);
		}

		$result['info'] = $shelter_profiles;

		$this->template->content = View::factory('petfinder_info', $result);
	}

}