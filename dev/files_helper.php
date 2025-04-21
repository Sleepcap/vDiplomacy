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

/* This helper-file allows for display and download of the server-side stored variants.
 * It takes great care not to give away files out of the variants-directory.
 */
 
chdir ('..');
require_once('header.php');

// The variantID
$variantID = isset($_REQUEST['variantID']) ? $_REQUEST['variantID'] : '0'; 
if (!(isset(Config::$variants[$variantID]))) $variantID = 0;
if (isset(Config::$hiddenVariants) && in_array($variantID,Config::$hiddenVariants) && $User->type['Guest']) $variantID = 0;
 
// What to do
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '' ; 

// Users can only access these 3 directories.
$basedir = isset($_REQUEST['basedir']) ? $_REQUEST['basedir'] : '/'; 
switch($basedir) {
	case '/classes/'        : $basedir = '/classes/';        break;
	case '/resources/'      : $basedir = '/resources/';      break;
	case '/interactiveMap/' : $basedir = '/interactiveMap/'; break;
	default                 : $basedir = '/';
}

// The filename (only letters, numbers and "." or "-" allowed...
$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : ''; 
$file = preg_replace('/[^A-z0-9 \(\)\-\.]/i', '', basename($file));

/*
 * View a file
 */
if ($action == 'view' && $variantID != 0)
{
	$filename = "variants/" . Config::$variants[$variantID] . $basedir.$file;
	if (file_exists($filename))
	{
		header("Content-type: text/plain; charset=utf-8");
		header("Content-disposition: inline; filename=".$file);
		readfile($filename);
	}
	exit;
}

/*
 * Download the sourcecode in a big zip-file
 */
if ($action == 'download' && $variantID != '0')
{
	$variant = libVariant::loadFromVariantID($variantID);
	$version= (isset($variant->version)?'_V'.$variant->version:'');
	$code   = (isset($variant->codeVersion)?'_C'.$variant->codeVersion:'');
	$filename=libCache::dirName('VarDownloads').'/'.$variant->name.str_replace('.','_',$version).str_replace('.','_',$code) . '.zip';
	if (!file_exists($filename)) {
		$zip = new ZipArchive();

		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE)
			exit("cannot open <$filename>\n");
		
		$zip->addEmptyDir($variant->name);
		$zip->addEmptyDir($variant->name.'/cache');
		$zip->addEmptyDir($variant->name.'/classes');
		$zip->addEmptyDir($variant->name.'/resources');
                if(file_exists($variant->name.'/interactiveMap')) $zip->addEmptyDir($variant->name.'/interactiveMap');
		foreach (glob($variant->name. '/classes/*') as $file) $zip->addFile($file);
		foreach (glob($variant->name. '/resources/*') as $file) $zip->addFile($file);
                if(file_exists($variant->name.'/interactiveMap')) foreach (glob($variant->name. '/interactiveMap/*') as $file) $zip->addFile($file);
		$zip->addFile($variant->name. '/variant.php');
		$zip->addFile($variant->name. '/install.php');
		if (file_exists($variant->name. '/rules.html'))
			$zip->addFile($variant->name. '/rules.html');
		
		$zip->close();		
	}
	
	header("Content-type: application/force-download");
	header("Content-Transfer-Encoding: Binary");
	header("Content-length: ".filesize($filename));
	header("Content-disposition: attachment; filename=".basename($filename));
	readfile($filename); 	
	exit;
}
