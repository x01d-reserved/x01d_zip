<?php

/*
 * x01d Zip script
 *
 * @description Recursively zip current directory (where script is located) to archive.zip
 * @version 1.0
 * @author index01d
 * @created 2011/02/26 6:00 PM GMT
 * 
 * @usage: Put the script into target directory and run http://server/path/to/script/x01d_zip.php 
 *         Result will be available at http://server/path/to/script/archive.zip
 *	   Attention! Don't forget to remove the script and archive after downloading!
 */

/*
 * Zip function
 *
 * @desc Zip (string)$source directory to (string)$destination archive
 *
 */
function Zip($source, $destination)
{
	if (extension_loaded('zip') === true)
	{
		if (file_exists($source) === true)
		{
			$zip = new ZipArchive();

			if ($zip->open($destination, ZIPARCHIVE::CREATE) === true)
			{
				$source = realpath($source);

				if (is_dir($source) === true)
				{
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

					foreach ($files as $file)
					{
						$file = realpath($file);

						if (is_dir($file) === true)
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
						else if (is_file($file) === true)
							$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
					}
				}
				else if (is_file($source) === true)
					$zip->addFromString(basename($source), file_get_contents($source));
			}

			return $zip->close();
		}
	}

	return false;
}

// Header
echo "<b>x01d zipper v1.0</b><br>";

$dst = "archive.zip";

// Zip and put link into page
if(Zip('./', './'.$dst))
{
	$url = "http://".$_SERVER["SERVER_NAME"].dirname($_SERVER['PHP_SELF'])."/".$dst;
	echo "Succesfully zipped! <br><b>Attention!</b> Don't forget to remove archive and this script after downloading!"
	      ."<br><a href=\"".$url."\">Download $dst</a>";
}
else
	echo "Fail!";
?>
