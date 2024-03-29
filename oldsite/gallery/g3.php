<?php
$imagePath = 'images';
$configFName = 'config.ini';
// default values, if not found in config.ini
$configData['title'] = 'photo gallery - g3';
$configData['narrative'] = '<p>This page is an experiment with alternative layout. The title is moved entirely over the navbar. Two rows of thumbs can be used.</p><p>And, the random image function has been moved to the title.</p><p>';
$configData['footer'] = 'footerstuff';
$configData['showFirst'] = 1;
$configData['originalWidth'] = 1500; // not presently used
$configData['originalHeight'] = 1500; // not presently used
$configData['previewWidth'] = 800;
$configData['previewHeight'] = 450;
$configData['thumbWidth'] = 150;
$configData['thumbHeight'] = 150;

//$configFile=$imagePath.'/'.$configFName;
//if (file_exists($configFile)) { $configData=array_merge($configData, parse_ini_file($configFile)); }

// identify the imageSets
$imageSetList = array();
foreach (glob($imagePath . '/*/*/originals', GLOB_ONLYDIR) as $f) {
   $iS = array(
      'galleryDirectory' => basename(dirname(dirname($f))),
      'galleryTitle' => ltrim(str_replace('_',' ',basename(dirname(dirname($f)))),'0..9'),
      'imageSetDirectory' => basename(dirname($f)),
      'title' => ltrim(str_replace('_',' ',basename(dirname($f))),'0..9'),
      'imageSetPath' => dirname($f),
      'imageSetFiles' => glob($f.'/*.jpg'));
   $config=dirname($f).'/'.$configFName;
   if (file_exists($config)) {
      $iS=array_merge($iS, parse_ini_file($config)); 
   }
   $imageSetList[dirname($f)] = $iS;
}
// identify the galleries
$galleryList = array();
foreach ($imageSetList as $i) {
   $galleryList[$i['galleryDirectory']]['title']=$i['galleryTitle'];
}
foreach (array_keys($galleryList) as $k) {
   $config=$imagePath.'/'.$k.'/'.$configFName;
   if (file_exists($config)) {
      $galleryList[$k]=array_merge($galleryList[$k], parse_ini_file($config)); 
   }
}
// generate any missing thumbs and previews
foreach ($imageSetList as $iS) {
   foreach ($iS['imageSetFiles'] as $img) {
      $thumb=str_replace('originals','thumbs',$img);
      $preview=str_replace('originals','previews',$img);
      if (!file_exists(dirname($thumb))){mkdir(dirname($thumb));}
      if (!file_exists(dirname($preview))){mkdir(dirname($preview));}
      if (!file_exists($thumb)){
         $imgR = imagecreatefromjpeg($img);
         $imgSize=getimagesize($img);
         $w=$imgSize[0];
         $h=$imgSize[1];
         $maxW=$configData['thumbWidth'];
         $maxH=$configData['thumbHeight'];
         if (($w/$maxW) >= ($h/$maxH)) {
            $newW = $maxW;
            $newH = $h * ($maxW/$w);
         }
         else {
            $newH = $maxH;
            $newW = $w * ($maxH/$h);
         }
         $newR = imagecreatetruecolor($newW, $newH);
         imagecopyresized($newR, $imgR, 0, 0, 0, 0, $newW, $newH, $w, $h);
         imagejpeg($newR,$thumb,80);
      }
      if (!file_exists($preview)){
         $imgR = imagecreatefromjpeg($img);
         $imgSize=getimagesize($img);
         $w=$imgSize[0];
         $h=$imgSize[1];
         $maxW=$configData['previewWidth'];
         $maxH=$configData['previewHeight'];
         if (($w/$maxW) >= ($h/$maxH)) {
            $newW = $maxW;
            $newH = $h * ($maxW/$w);
         }
         else {
            $newH = $maxH;
            $newW = $w * ($maxH/$h);
         }
         $newR = imagecreatetruecolor($newW, $newH);
         imagecopyresized($newR, $imgR, 0, 0, 0, 0, $newW, $newH, $w, $h);
         imagejpeg($newR,$preview,80);
      }
   }
}
?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $configData['title']; ?></title>
  <link type="text/css" rel="stylesheet" href="g3.css" />
  <script type="text/javascript" src="g3.js" > </script>
</head>
<body>

<?php
//echo '<pre>';
//print_r($config);
//print_r($galleryList);
//echo '</pre>';
?>

<div id="header" class="test" onclick="previewRandomImage();">
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
<?php
foreach ($imageSetList as $iS) {
// imageset wrapper
?>
<div class="
<?php
   if ($activeSet == $iS['imageSetPath']) {
      if ($configData['showFirst'] == 1) {echo 'activeimgs';}else{echo 'inactiveimgs';}
   }
   else {
      echo 'inactiveimgs';
   }
?>
">
   <div class="imageset"><span class="key"><?php echo $iS['imageSetPath']; ?></span>
      <div class="thumbs">
<?php
   // thumbs
   if ($configData['showFirst'] == 1) { $firstImg=false; } else { $firstImg=true; }
   foreach ($iS['imageSetFiles'] as $img) {
      $thumb=str_replace('originals','thumbs',$img);
?>
         <img class="<?php echo ($firstImg?'unselected':'selected'); ?>" src="<?php echo $thumb; ?>" alt="<?php echo $img; ?>" onclick="setPreview(this);" />
<?php
      if ($firstImg===false) {$firstImg=true;}
   }
?>
      </div>
      <div class="preview">
<?php
   // previews
   if ($configData['showFirst'] == 1) { $firstImg=false; } else { $firstImg=true; }
   foreach ($iS['imageSetFiles'] as $img) {
      $preview=str_replace('originals','previews',$img);
?>
         <a href="<?php echo $img; ?>">
            <img class="<?php echo ($firstImg?'unselected':'selected'); ?>" src="<?php echo $preview; ?>" alt="<?php echo $img; ?>" />
         </a>
<?php
      if ($firstImg===false) {$firstImg=true;}
   }
?>
      </div>
   </div>
</div>
<?php
}
?>
</div>

<div id="narrative">
   <?php echo $configData['narrative']; ?>
</div>
<div id="footer">
   <?php echo $configData['footer']; ?>
</div>
</body>
</html>

