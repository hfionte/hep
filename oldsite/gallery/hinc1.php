<?php
$imagePath = 'images';
$configFName = 'config.ini';
// default values, if not found in config.ini
$configData['title'] = 'Ajax Gallery 1';
$configData['narrative'] = '<p>Based on g2".</p><p>But this page gets image sets via async calls when selected.</p>';
$configData['footer'] = 'begun 7/27/2007';
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

