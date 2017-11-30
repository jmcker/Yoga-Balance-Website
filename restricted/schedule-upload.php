<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Schedule Upload</title>
    </head>
    <body>

<?php

// Upload and Rename File

if (isset($_POST['submit']))
{
    $month = $_POST['month'];
    $overwritecurrent = $_POST['overwritecurrent'];
	$filename = $_FILES["file"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file name
	$file_ext = substr($filename, strripos($filename, '.')); // get file extension
	$filesize = $_FILES["file"]["size"];
	$allowed_file_types = array('.pdf');	

	if (in_array($file_ext, $allowed_file_types) && ($filesize < 200000))
	{	
		// Rename file
		$newfilename = "current-schedule-yoga-balance" . $file_ext;
        $backupfilename = strtolower(date('F')) . "-" . date('Y') . "-schedule-yoga-balance" . $file_ext;
        if(TRUE) //file_exists("../downloads/schedule/" . $backupfilename
        {
            $month = trim(stripslashes(strtolower($month)));
            if (strlen($month) > 0 && strlen($month) < 10)
            {
                $backupfilename = $month . "-" . date('Y') . "-schedule-yoga-balance" . $file_ext;
            }
            else
            {
                echo "Month was not formatted correctly. Please try again.";
                echo "<br><br><a href=\"javascript:history.back()\">Back</a>";
                unlink($_FILES["file"]["tmp_name"]); // delete temp file
                exit;
            }
        }
        copy($_FILES["file"]["tmp_name"], "../downloads/schedule/" . $backupfilename); // Copy file under new backup name


        // Ensure that current schedule is preserved if user desired
        if (file_exists("../downloads/schedule/" . $newfilename) and !$overwritecurrent)
        {
            echo "Option to overwrite current schedule was not enabled.<br>";
            echo "File <b>" . $backupfilename . "</b> was uploaded, but current schedule was preserved.<br><br>";
            echo "If you would like to overwrite the current schedule, please try again with the overwrite option enabled. <br>";
            echo "<a href=\"javascript:history.back()\">Back</a><br><br>";
        }
        else
        {
            copy($_FILES["file"]["tmp_name"], "../downloads/schedule/" . $newfilename);
            echo "<b>Files uploaded successfully.</b>";
        }
        
        echo "<iframe src=\"../downloads/schedule/\" id=\"fileviewer\" width=\"90%\" height=\"600px\"></iframe>";
        	
	}
	elseif (empty($file_basename))
	{	
		// file selection error
		echo "Please select a file to upload.";
        echo "<br><br><a href=\"javascript:history.back()\">Back</a>";
	} 
	elseif ($filesize > 200000)
	{	
		// file size error
		echo "The file you are trying to upload is too large.";
        echo "<br><br><a href=\"javascript:history.back()\">Back</a>";
	}
	else
	{
		// file type error
		echo "Only these file types are allowed for upload: " . implode(', ',$allowed_file_types);
		unlink($_FILES["file"]["tmp_name"]);
        echo "<br><br><a href=\"javascript:history.back()\">Back</a>";
	}
}

?>
        
    </body>
</html>
