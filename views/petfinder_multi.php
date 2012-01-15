<?php  defined('SYSPATH') or die('No direct script access.');

$output = '';

$output .= '<h1>Our list of adoptable pets</h1>';

$count_pets = count($pet);

for($i = 0; $i < $count_pets; $i++)
{
	$current_pet = $pet[$i];

	$output .= '<div class="petinfo">';
	$output .= HTML::anchor($url_details.$current_pet->id.'-'.URL::title($current_pet->name), '<h3>'.$current_pet->name.'</h3>');

	$output .= '<div class="petfinderThumb">';
	$thumbnail = $current_pet->media->photos->photo[1];
	$output .= HTML::anchor($url_details.$current_pet->id.'-'.URL::title($current_pet->name), HTML::image($thumbnail));
	$output .= '</div>';

	$output .= '<div class="petfinderStats petfinderSnippet">';
	$output .= '<dl class="petfinderBio">';
	$output .= '<dt class="petfinderBioLabel">Age</dt><dd class="petfinderBioData">'.$current_pet->age.'</dd>';

	if(array_key_exists((string)$current_pet->sex, $legend['legend_sex']))
		$output .= '<dt class="petfinderBioLabel">Sex</dt><dd class="petfinderBioData">'.$legend['legend_sex'][(string)$current_pet->sex].'</dd>';

	$output .= '<dt class="petfinderBioLabel">Breed</dt><dd class="petfinderBioData">';

	if($current_pet->mix == 'yes')
		$output .= 'Mix of ';

	foreach($current_pet->breeds->breed as $pet_breed)
	{
		$output .= $pet_breed.', ';
	}

	$output .= '</dd>';
	$output .= '</dl>';

	$output .= '</div>';
	$output .= '</div>';
}

echo $output;
