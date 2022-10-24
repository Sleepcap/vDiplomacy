<?php
/*
    Copyright (C) 2018 Oliver Auth

	This file is part of vDiplomacy.

    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with vDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

// For security reasons fileupload and all variables are ussually discarded in header.php. Save this in constants.
$uploadname=(isset($_FILES['upload']['name']))    ? $_FILES['upload']['name']     : '' ; // The uploaded filename
$uploadtmp =(isset($_FILES['upload']['tmp_name']))? $_FILES['upload']['tmp_name'] : '' ; // the tmp-filename from PHP
define('UP',$uploadname);

// What to do
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '' ; 

// Users can only access these 4 directories.
$basedir = isset($_REQUEST['basedir']) ? $_REQUEST['basedir'] : '/'; 
switch($basedir) {
	case '/classes/'        : $basedir = '/classes/';        break;
	case '/resources/'      : $basedir = '/resources/';      break;
	case '/interactiveMap/' : $basedir = '/interactiveMap/'; break;
	default                 : $basedir = '/';
}

// The filename (only letters, numbers and "." or "-" allowed...
$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : UP; 
$file = preg_replace('/[^A-z0-9 \(\)\-\.]/i', '', basename($file));

// Filled with the new content after editing
$updatedfile = isset($_REQUEST['updatedfile'])? $_REQUEST['updatedfile']: '' ; 

// a message to diaplay.
$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '' ; 
switch($msg) {
	case '1'  : $msg = 'Failed to save '.$basedir.$file.' !';                  break;
	case '2'  : $msg = 'File '.$basedir.$file.' saved.';                       break;
	case '3'  : $msg = 'File '.$basedir.$file.' uploaded.';                    break;
	case '4'  : $msg = 'File '.$basedir.$file.' deleted.';                     break;
	case '5'  : $msg = 'File '.$basedir.$file.' verified.';                    break;
	case '6'  : $msg = 'Editing canceled. File '.$basedir.$file.' not saved!'; break;
	default   : $msg = '';
}

// You can't edit or upload a install.php. It causes all sort of strange problems.
if (($action == 'upload' || $action == 'filesave') && $file=='install.php')
{
	$msg = 'Editing or uploading of a modified install.php is for experts only.<br>Please contakt an admin if you really need to do this.';
	$action = ''; $file='';
}

// You can't edit or upload a variant.php. It causes all sort of strange problems.
if (($action == 'upload' || $action == 'filesave') && $file=='variant.php')
{
	$msg = 'Editing or uploading of a modified variant.php is for experts only.<br>Please contakt an admin if you really need to do this.';
	$action = ''; $file='';
}

/*
 * Show the variant-files with some edit/upload/delete  possibilities for variant developers
 */
 
print '<b>Variant: '.$selectVariantForm.'</b>';

if ($variantID != 0)
{

	print '<form style="display: inline" action="dev/files_helper.php" method="POST">
				<input type="hidden" name="variantID" value="'.$variantID.'" />
				<input type="hidden" name="tab" value="Files" />
				<input type="hidden" name="action" value="download" />
				<input type="submit" value="Download as zip" />
			</form>
		<div class="hr"></div>';
		
	$variantbase = "variants/" . Config::$variants[$variantID];

	$edit = false;
	if ($User->type['DevAdmin']) $edit=true;
	if (isset(Config::$devs))
		if (array_key_exists($User->username, Config::$devs)) 
		 if (in_array(Config::$variants[$variantID], Config::$devs[$User->username]))
			$edit = true;
	
	if ($edit)
	{
		if ($msg != '')
			print '<li class="formlisttitle">'.$msg.'</li>';
			
		if (($action == 'edit') && (file_exists ($variantbase.$basedir.$file)))
		{	
			print '
				<li class="formlisttitle">Edit: '.$basedir.$file.': 
					<form  style="display: inline" action="'. $_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=6&file='.$file.'&basedir='.$basedir.'" method="post">
					<input type="submit" value="Cancel">
					</form>
					<form  style="display: inline" action="'. $_SERVER['SCRIPT_NAME'] .'" method="post">
					<input type="hidden" name="tab" value="Files" />
					<input type="submit" value="Save Changes"></li>
					<input type="hidden" name="action" value="filesave" />
					<input type="hidden" name="variantID" value="' . $variantID . '" />
					<input type="hidden" name="basedir" value="'.$basedir.'"/>
					<input type="hidden" name="file" value="'.$file.'"/>
					<textarea rows="20" style="border: 1px solid #666666; font-family: courier;" name="updatedfile">';
			//Open the file chosen in the select box in the previous form into the text area
			$file2open = fopen($variantbase.$basedir.$file, "r");
			$current_data = @fread($file2open, filesize($variantbase.$basedir.$file));
			fclose($file2open);
			// Recplace a "</textarea>" tag so it does not break the layout
			$current_data = str_ireplace("</textarea>", "<END-TA-DO-NOT-EDIT>", $current_data);
			echo $current_data;
			print '</textarea></form></div>';
			libHTML::footer();
			exit;
		}
		
		// Delete the global css if a CSS file got edited or upload
		if (($action == 'upload' || $action == 'filesave') && !stripos($file, '.css') === false)
			foreach (glob(libCache::Dirname('css').'/variants-*.css') as $cssfilename)
				unlink($cssfilename);
				
		if (($action == 'filesave') && (file_exists ($variantbase.$basedir.$file)))
		{
			if (file_exists ($variantbase.$basedir.$file.' (wait for verify)'))
				copy($variantbase.$basedir.$file.' (wait for verify)', $variantbase."/cache/".date("ymd-His")."-edit-".$file);
			elseif (file_exists ($variantbase.$basedir.$file))
				copy($variantbase.$basedir.$file, $variantbase."/cache/".date("ymd-His")."-edit-".$file);
				
			if (stripos($file, '(wait for verify)') === false)
				if (!stripos($file, 'php') === false)
					$file .= ' (wait for verify)';
			$file2ed = fopen($variantbase.$basedir.$file, "w+");
			// Recplace a "</textarea>" tag so it dows not break the layout
			$updatedfile = str_ireplace("<END-TA-DO-NOT-EDIT>", "</textarea>", $updatedfile);
			//Remove any slashes that may be added do to " ' " s.  Thats a single tick, btw.
			$updatedfile = stripslashes($updatedfile);
			$ok = fwrite($file2ed,$updatedfile);
			fclose($file2ed);
			
			if (!$ok)
				echo '<script type="text/javascript">top.location.replace("'.$_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=1&file='.$file.'&basedir='.$basedir.'");</script>';
			else
				echo '<script type="text/javascript">top.location.replace("'.$_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=2&file='.$file.'&basedir='.$basedir.'");</script>';
			exit;
		}
		
		if (($action == 'delete') && (file_exists ($variantbase.$basedir.$file)))
		{
			rename($variantbase.$basedir.$file, $variantbase."/cache/".date("ymd-His")."-del-".$file);
			echo '<script type="text/javascript">top.location.replace("'.$_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=4&file='.$file.'&basedir='.$basedir.'");</script>';
			exit;
		}

		if ($action == 'upload') {
			if (!($file == 'install.php' && file_exists($variantbase.'/cache/install-backup.php')))
			{
				if ($file != '')
				{
					if (file_exists ($variantbase.$basedir.$file.' (wait for verify)'))
						copy($variantbase.$basedir.$file.' (wait for verify)', $variantbase."/cache/".date("ymd-His")."-upl-".$file);
					elseif (file_exists ($variantbase.$basedir.$file))
						copy($variantbase.$basedir.$file, $variantbase."/cache/".date("ymd-His")."-upl-".$file);
					if (!stripos($file, 'php') === false)
						$file .= ' (wait for verify)';
					if($basedir == '/interactiveMap/' && !file_exists($variantbase.$basedir)) mkdir($variantbase.'/interactiveMap', 0755);
					rename ($uploadtmp, $variantbase.$basedir.$file);
					chmod($variantbase.$basedir.$file, 0644);
				}
			}
			
			echo '<script type="text/javascript">top.location.replace("'.$_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=3&file='.$file.'&basedir='.$basedir.'");</script>';
			exit;
		}

		if ($action == 'verify' && ($User->type['DevAdmin'])) {
			if (file_exists ($variantbase.$basedir.$file))
			{
				$newfile = substr($file, 0, -18);
				rename($variantbase.$basedir.$file, $variantbase.$basedir.$newfile);
			}
			echo '<script type="text/javascript">top.location.replace("'.$_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&msg=5&file='.$newfile.'&basedir='.$basedir.'");</script>';
			exit;
		}
	
		print '<li class="formlisttitle">
			<form enctype="multipart/form-data" 
				action="'. $_SERVER['SCRIPT_NAME'] .'"
				method="POST">
			<input type="hidden" name="tab" value="Files" />
			<input type="hidden" name="variantID" value="' . $variantID . '" />
			<input type="hidden" name="action" value="upload" />
			Upload file: <input type="file" name="upload" /> - directory:
			<select name="basedir">
				<option value="/" selected>             /               </option>
				<option value="/classes/">              /classes/       </option>
				<option value="/resources/">            /resources/     </option>
				<option value="/interactiveMap/">       /interactiveMap/</option>
			</select>
			<input type="submit" value="Upload File" />
			</form></li>';
	}
	
	// print the variant-files in a nice grid
	print "<li class=\"formlisttitle\">Variant-Files:";
	print "<table border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n";
	foreach (array("/","/classes/", "/resources/","/interactiveMap/") as $dirname) {
		
		if(!file_exists($variantbase.$dirname)) continue;
				
		print("<th>" . $dirname . "</th><tr>\n");
		
		$files = array();
		$dir = opendir($variantbase . $dirname);
		while (false !== ($file = readdir($dir)))
			if (!is_dir($variantbase . $dirname . $file))
				$files[] = $file;
		closedir($dir);
		sort($files);
		
		foreach ($files as $file) {
			// Call the php and html files with a wrapper to display the content...
			if (substr($file, -3) == 'php' || substr($file, -4) == 'html' || substr($file, -3) == 'htm' || substr($file, -7) == 'verify)')
				print('<td><a href="dev/files_helper.php?&variantID='.$variantID.'&action=view&file='.$file.'&basedir='.$dirname.'">'.$dirname.$file.'</a></td>');
			else
				print("<td><a href=\"$variantbase$dirname$file\">$dirname$file</a></td>");

			if ($edit == true)
			{
				// Add a delete and edit button if we have a developer: (But not on install.php, and not on variant.php.
				if ($file != "install.php" && $file != "variant.php" )
				{
					print('<td><a href="' . $_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&action=delete&file='.$file.'&basedir='.$dirname.'">Delete</a></td>');
				
					if (substr($file, -3) == 'php' || substr($file, -3) == 'txt'  || substr($file, -7) == 'verify)' || substr($file, -4) == 'html' || substr($file, -4) == 'htm')
						print('<td><a href="' . $_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&action=edit&file='.$file.'&basedir='.$dirname.'">Edit</a></td>');
				}
			}
			
			// Superuser can verify files:
			if (($User->type['DevAdmin']) && substr($file, -7) == "verify)")
				print('<td><a href="' . $_SERVER['SCRIPT_NAME'].'?tab=Files&variantID='.$variantID.'&action=verify&file='.$file.'&basedir='.$dirname.'">Verify</a></td>');
				
			print("</tr>\n");
		}
	}
	print "</table>\n";
}
print '</div>';
libHTML::footer();

?>
