<?php
$imagePath = 'images';
$configFName = 'config.ini';
// default values, if not found in config.ini
$configData['title'] = 'Slow Gallery';
$configData['narrative'] = 'This page loads all the images in the page before becoming active.';
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
  <link type="text/css" rel="stylesheet" href="g1.css" />
  <script type="text/javascript" src="g1.js" > </script>
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
   echo '<dl class="' . ($firstGallery?'open':'closed') . '"><dt>' . $galleryList[$k]['title'] . '</dt>';
   foreach ($imageSetList as $iS) {
      if ($k == $iS['galleryDirectory']) {
         echo '<dd class="' . ($activeSet?'closed':'open') . '"><span class="key">' . $iS['imageSetPath'] . '</span>' . $iS['title'] . '</dd>';
         if ($activeSet === false) { $activeSet=$iS['imageSetPath'];}
      }
   }
   $firstGallery=false;
   echo '</dl>';
}
?>

</div>
<div id="frame">

<?php
foreach ($imageSetList as $iS) {
   // imageset wrapper
   echo '<div class="';
   if ($activeSet == $iS['imageSetPath']) {
      if ($configData['showFirst'] == 1) {echo 'activeimgs';}else{echo 'inactiveimgs';}
   }
   else {
      echo 'inactiveimgs';
   }
   echo '"><div class="imageset"><span class="key">'.$iS['imageSetPath'].'</span>';
   // thumbs wrapper
   echo '<div class="thumbs">';
   // thumbs
   if ($configData['showFirst'] == 1) { $firstImg=false; } else { $firstImg=true; }
   foreach ($iS['imageSetFiles'] as $img) {
      $thumb=str_replace('originals','thumbs',$img);
      echo '<img src="'.$thumb.'" alt="'.$img.'" class="';
      echo ($firstImg?'unselected':'selected');
      echo '" />';
      if ($firstImg===false) {$firstImg=true;}
   }
   echo '</div>';
   // previews wrappers
   echo '<div class="preview">';
   // previews
   if ($configData['showFirst'] == 1) { $firstImg=false; } else { $firstImg=true; }
   foreach ($iS['imageSetFiles'] as $img) {
      $preview=str_replace('originals','previews',$img);
      echo '<a href="'.$img.'">';
      echo '<img src="'.$preview.'" alt="'.$img.'" class="'. ($firstImg?'unselected':'selected') . '" />';
      echo '</a>';
      if ($firstImg===false) {$firstImg=true;}
   }
   // close previews
   echo '</div>';
   // close imageset
   echo '</div></div>';
}
?>

</div>
<div id="narrative">
<div id="randomize">Show Random Image</div>
   <?php echo $configData['narrative']; ?>
</div>
<div id="footer">
   <?php echo $configData['footer']; ?>
</div>
</body>
</html>

