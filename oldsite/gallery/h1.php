<?php
require_once ('hinc1.php');
?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $configData['title']; ?></title>
  <link type="text/css" rel="stylesheet" href="h1.css" />
  <script type="text/javascript" src="h1.js" > </script>
</head>
<body>

<?php
//echo '<pre>';
//print_r($config);
//print_r($galleryList);
//echo '</pre>';
?>

<div id="header" class="test">
   <h1><?php echo $configData['title']; ?></h1>
</div>
<div id="sidenav" class="test">

<?php
if ($configData['showFirst'] == 1) {
   $firstGallery=true; // to open first gallery
   $activeSet=false; // to open first imageset
}
else {
   $firstGallery=false; // to keep all galleries closed
   $activeSet=true; // to keep all imagesets closed
}
foreach (array_keys($galleryList) as $k) {
?>
<dl class="<?php echo ($firstGallery?'open':'closed'); ?>">
   <dt onclick="setPreviewFirst(activateImageSet(this.parentNode.getElementsByTagName('dd')[0]));openGalleryList(this);">
      <?php echo $galleryList[$k]['title'];?>
   </dt>
<?php
   foreach ($imageSetList as $iS) {
      if ($k == $iS['galleryDirectory']) {
?>
   <dd class="<?php echo ($activeSet?'closed':'open'); ?>" onclick="activateImageSet(this);">
   <span class="key"><?php echo $iS['imageSetPath'];?></span>
   <?php echo $iS['title'];?>
   </dd>
<?php
         if ($activeSet === false) { $activeSet=$iS['imageSetPath'];}
      }
   }
   $firstGallery=false;
?>
</dl>
<?php
}
?>
</div>
<div id="frame">
</div>

<div id="narrative">
<div id="randomize" onclick="previewRandomImage();">Show Random Image</div>
   <?php echo $configData['narrative']; ?>
</div>
<div id="footer">
   <?php echo $configData['footer']; ?>
</div>
</body>
</html>

