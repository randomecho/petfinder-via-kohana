<div class="petinfo">
<ul>
<?php

if (isset($info))
{
	for ($i = 0, $n = count($info); $i < $n; $i++)
	{
		echo '<li>'.$info[$i].'</li>';
	}
}

?>
</ul>
</div>