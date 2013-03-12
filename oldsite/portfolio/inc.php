<?php
$imagePath = '.';
$configFName = 'config.ini';
// default values, if not found in config.ini
$configData['title'] = 'new layout <br />8/10/2007';
$configData['narrative'] = '<p>Push the image display area up. Move the heading to the space above the navigation bar.</p><p>Move the controls above the list of galleries.</p>';
$configData['footer'] = 'footer stuff';
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
foreach (glob($imagePath . '/originals/*/*', GLOB_ONLYDIR) as $f) {
   $iS = array(
      'galleryDirectory' => basename(dirname($f)),
      'galleryTitle' => ltrim(str_replace('_',' ',basename(dirname($f))),'0..9'),
      'imageSetDirectory' => basename($f),
      'title' => ltrim(str_replace('_',' ',basename($f)),'0..9'),
      'imageSetPath' => $f,
      'imageSetFiles' => glob($f.'/*.jpg'));
   $config=dirname($f).'/'.$configFName;
   if (file_exists($config)) {
      $iS=array_merge($iS, parse_ini_file($config)); 
   }
   $imageSetList[$f] = $iS;
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
?>

