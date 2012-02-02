<?php  defined('SYSPATH') or die('No direct script access.');

$output = '';

$output .= '<h1>Our list of adoptable pets</h1>';

$count_pets = count($pet);

for($i = 0; $i < $count_pets; $i++)
{
	$current_pet = $pet[$i];

	$output .= '<div class="petinfo">';
	$output .= HTML::anchor($url_details.$current_pet->id.'-'.URL::title($current_pet->name), '<h3>'.$current_pet->name.'</h3>');

	if(isset($current_pet->media->photos) && count($current_pet->media->photos) > 0)
		$thumbnail = $current_pet->media->photos->photo[1];
	else
		$thumbnail = Kohana::$config->load('petfinder.image_none');

	$output .= '<div class="petfinderThumb">';
	$output .= HTML::anchor($url_details.$current_pet->id.'-'.URL::title($current_pet->name), HTML::image($thumbnail));
	$output .= '</div>';

	$output .= '<div class="petfinderStats petfinderSnippet">';
	$output .= '<dl class="petfinderBio">';
	$output .= '<dt class="petfinderBioLabel">Age</dt><dd class="petfinderBioData">'.$current_pet->age.'</dd>';

	if(array_key_exists((string)$current_pet->sex, $legend['legend_sex']))
		$output .= '<dt class="petfinderBioLabel">Sex</dt><dd class="petfinderBioData">'.$legend['legend_sex'][(string)$current_pet->sex].'</dd>';

	$output .= '<dt class="petfinderBioLabel">Breed</dt><dd class="petfinderBioData">';

	$count_breeds = count($current_pet->breeds->breed);

	if($count_breeds == 1)
	{
		$output .= $current_pet->breeds->breed;
	}
	else
	{
		$breeds = '';

		foreach($current_pet->breeds->breed as $pet_breed)
		{
			$breeds .= $pet_breed. ' and ';
		}

		$output .= substr($breeds, 0, strrpos($breeds, ' and'));
	}

	$output .= ($current_pet->mix == 'yes') ? ' mix' : '';

	$output .= '</dd>';
	$output .= '</dl>';
	$output .= '</div>';

	$output .= '</div>';
}

echo $output;

