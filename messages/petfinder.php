<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'status' => array(
		'A' => 'Adoptable',
		'H' => 'On hold',
		'P' => 'Pending adoption',
		'X' => 'Adopted',
	),

	'status_heading' => array(
		'A' => ', one of our adoptable pets',
		'H' => 'is currently on hold',
		'P' => 'is pending adoption',
		'X' => ', one of our adopted pets',
	),

	'legend_sex' => array(
		'M' => 'Male',
		'F' => 'Female',
	),

	'legend_size' => array(
		'S' => 'Small',
		'M' => 'Medium',
		'L' => 'Large',
		'XL' => 'Extra large',
	),

	'legend_options' => array(
		'altered' => 'spayed/neutered',
		'hasShots' => 'current on vaccinations',
		'housebroken' => 'house broken',
		'noCats' => 'prefers home with no cats',
		'noDogs' => 'prefers home with no dogs',
		'noKids' => 'prefers home with no children',
		'specialNeeds' => 'requires special care',
	),

	'error' => array(
		'none' => 'Our shelter currently has no adoptable pets. Please check back soon.',
		'none_heading' => 'No pets at this time',
		'invalid' => 'Invalid pet ID.',
		'invalid_heading' => 'No pet found.',
	),

	'meta' => array(
		'title' => 'Adoptable pets',
		'invalid' => 'No pet found',
	),

);
