<div class="petinfo">
<h1><?php echo $info['name'] ?></h1>
<?php

if (isset($info['media'][1]['x']))
{
	$thumbnail = $info['media'][1]['x'];
	echo HTML::image($thumbnail, array('alt' => $info['name']));
}

?>
<div class="petfinderStats">
<div class="petfinderInfo">
<?php echo $info['description'] ?>
</div>
<dl class="petfinderBio">
<dt class="petfinderBioLabel">Petfinder ID</dt><dd class="petfinderBioData"><?php echo $info['id'] ?></dd>
<dt class="petfinderBioLabel">Status</dt><dd class="petfinderBioData"><?php echo $info['status'] ?></dd>
<dt class="petfinderBioLabel">Age</dt><dd class="petfinderBioData"><?php echo $info['age'] ?></dd>
<dt class="petfinderBioLabel">Sex</dt><dd class="petfinderBioData"><?php echo $info['sex'] ?></dd>
<dt class="petfinderBioLabel">Breed</dt><dd class="petfinderBioData">
<?php

if (Arr::is_array($info['breed']))
{
	echo implode(' and ', $info['breed']);
}
else
{
	echo $info['breed'];
}

?>
</dd>
<?php

if (isset($info['options']))
{
?>
	<dt class="petfinderBioLabel">Details</dt><dd class="petfinderBioData">
	<ul>
<?php
	$option_count = count($info['options']);
	for ($i = 0; $i < $option_count; $i++)
	{
		echo '<li>'.$info['options'][$i].'</li>';
	}
?>
	</ul>
	</dd>
<?php
}

if ($info['status'] == 'A')
{
?>
	<dt class="petfinderBioLabel">Contact</dt>
<?php
	$name_contact = ($info['contact_name'] != '') ? $info['contact_name'] : 'Contact us';

	if ($info['contact_email'] != '')
	{
		$contact_method = HTML::mailto($info['contact_email'].'?subject=Petfinder: '.$info['name'], $name_contact);
	}
	else
	{
		$contact_method = HTML::anchor('http://www.petfinder.com/petdetail/'.$info['id'], 'See contact details on Petfinder');
	}

	?>
	<dd class="petfinderBioData"><?php echo $contact_method ?></dd>
	<?php
}
?>
	<dt class="petfinderBioLabel">Shelter Contact</dt>
	<dd class="petfinderBioData">Address: <?php echo $info['contact_address1'] ?> <?php echo $info['contact_address2'] ?> <?php echo $info['contact_city'] ?> <?php echo $info['contact_state'] ?> <?php echo $info['contact_zip'] ?></dd>
	<dd class="petfinderBioData">Phone: <?php echo $info['contact_phone'] ?>, Fax: <?php echo $info['contact_fax'] ?></dd>
</dl>
</div>
</div>