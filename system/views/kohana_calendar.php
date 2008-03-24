<?php

/**
 * @todo This needs to be moved to an i18n file, or be configured in the library.
 */
$headings = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

?>
<table class="calendar">
<caption><?php echo strftime('%B %Y', mktime(0, 0, 0, $month, 1, $year)) ?></caption>
<tr>
<?php

foreach ($headings as $day):

?>
<th><?php echo $day ?></th>
<?php

endforeach

?>
</tr>
<?php

foreach ($weeks as $week):

?>
<tr>
<?php

foreach ($week as $day):

list ($number, $current, $data) = $day;

if (is_array($data))
{
	$classes = $data['classes'];
	$output = empty($data['output']) ? '' : '<ul class="output"><li>'.implode('</li><li>', $data['output']).'</li></ul>';
}
else
{
	$classes = array();
	$output = '';
}

?>
<td class="<?php echo implode(' ', $classes) ?>"><span class="day"><?php echo $day[0] ?></span><?php echo $output ?></td>
<?php

endforeach

?>
</tr>
<?php

endforeach

?>
</table>
