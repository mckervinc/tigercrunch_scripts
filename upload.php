<?php
$upload_dir = "uploads/";
$upload_file = $upload_dir . basename($_FILES["imageToUpload"]["name"]);
$uploadSuccess = 1;
$imageType = pathinfo($upload_file, PATHINFO_EXTENSION);

//Check if there is a file
if (isset($_POST["submit"]))
{
	echo "file submitted";
}
else 
{
	echo "no file submitted";
	$uploadSuccess = 0;
}
//Check if file exists
if (!file_exists($upload_file))
{
	echo "Image does not exist";
}
//Check if file is an image
if ($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg" && $imageType != "gif")
{
  echo "wrong image type";
	$uploadSuccess = 0;
} 
else 
  {
    echo "correct type";
}
//If all is well, upload
if ($uploadSuccess)
{
	if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $upload_file))
		{
		  echo "It worked";
		  chmod($upload_file, 0777);
		}
	else
		{
		  echo "Didn't work";
		  echo $_FILES["imageToUpload"]["error"];
		}
}
else
	{echo "can't upload";}
?>
