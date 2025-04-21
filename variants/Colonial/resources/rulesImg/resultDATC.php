<?php

libHTML::pagebreak();

$tabl = $DB->sql_tabl("SELECT testID, testName, status, testDesc FROM wD_DATC WHERE variantID=".$Variant->id." ORDER BY testID");

print '
<div class="datc">
<a name="tests"></a><h4>Colonial Variant Test Cases</h4>
<table>
	';
$alternate=2;
$lastSectionID=-1;
while ( list($id, $name, $status, $description) = $DB->tabl_row($tabl) )
{

	if( $status=='Invalid' )
		$image = '(Invalid test)';
	else
		$image = '
			<a href="#" onclick="$(\'testimage'.$id.'\').src=\'variants/Colonial/resources/rulesImg/'.$id.'-large.map\'; return false;">'.
			'<img id="testimage'.$id.'" src="variants/Colonial/resources/rulesImg/'.$id.'-large.map-thumb" alt="Test ID #'.$id.' map thumbnail" />'.
			'</a>
			';

	$details = '<b>'.$name.'</b><br />'.$description;

	print '
<tr id="'.$name.'" class="threadalternate'.$alternate.'">
<td><p class="notice">'.$image.'</p></td>
<td><p>'.$details.'</p></td>
</tr>
		';
	
	$alternate = $alternate%2 + 1;
}
print '</table>';
print '</div>';

?>