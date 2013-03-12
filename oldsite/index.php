<?php
$imagePath = '.';
$configFile = 'portfolio.ini';
if (file_exists($configFile)) {$configData=parse_ini_file($configFile);}
// identify the imageSets
$imageSetList = array();
foreach (glob($imagePath . '/previews/*/*', GLOB_ONLYDIR) as $f) {
   $iS = array(
      'galleryDirectory' => basename(dirname($f)),
      'galleryTitle' => ltrim(str_replace('_',' ',basename(dirname($f))),'0..9'),
      'imageSetDirectory' => basename($f),
      'title' => ltrim(str_replace('_',' ',basename($f)),'0..9'),
      'imageSetPath' => $f,
      'imageSetFiles' => glob($f.'/*.jpg'));
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
<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo strip_tags($configData['title']); ?></title>
  <link type="text/css" rel="stylesheet" href="portfolio.css" />
  <script type="text/javascript" src="portfolio.js" > </script>
</head>
<body onload="startup();">
<div id="frame">
<div id="header">
   <h1><?php echo $configData['title']; ?></h1>
</div>
<div id="sidenav">
<?php
$firstGallery=true;
$firstKey=false; //first imageset in first gallery
$firstImg = true;
foreach (array_keys($galleryList) as $k) {
?>
<dl class="<?php echo($firstGallery?'open':'closed'); ?>">
   <dt onclick="clickGallery(this);">
      <?php echo $galleryList[$k]['title'];?>
   </dt>
<?php
   foreach ($imageSetList as $iS) {
      if ($k == $iS['galleryDirectory']) {
?>
   <dd class="<?php echo(!$firstKey?'open':'closed'); ?>">
   <?php echo '<span onclick="clickImageSet(this);">'.$iS['title'].'</span><br/>';
         foreach ($iS['imageSetFiles'] as $img) {
            echo'<div class="key">'.$img.'</div>';
            echo'<button class="'.($firstImg?'on':'off').'" onclick="clickStamp(this);"></button>';
            $firstImg=false; 
         }
   echo'</dd>';
         if (!$firstKey) {$firstKey=$iS['imageSetPath'];}
      }
   }
?>
</dl>
<?php
   $firstGallery=false;
}
?>
</div> <!-- close sidenav div -->
<div id="narrative">
   <?php echo $configData['narrative']; ?>
</div>
<div id="controls">
   <div class="buttongroup">
      <span id="previous" class="button" onclick="previousImage();">&nbsp;&lt;&nbsp;</span>
      <span id="randomize" class="button" onclick="randomImage();">random</span>
      <span id="next" class="button" onclick="nextImage();">&nbsp;&gt;&nbsp;</span>
   </div>
   <div class="buttongroup"><span>show:</span>
      <span id="showcontinuous" class="button" onclick="showContinuous();">next</span>
      <span id="showrandom" class="button" onclick="showRandom();">random</span>
   </div>
</div>
<div id="zone">
<?php
$firstPreview=true;
foreach ($imageSetList as $iS) {
   foreach ($iS['imageSetFiles'] as $img) {
?>
<div class="<?php echo(($img==$firstPreview)?'on':'off'); ?>">
<span class='key'><?php echo $img ?></span>
<div class="zonetext">
<?php 
$textFile = str_replace('.jpg','.txt',$img);
if (file_exists($textFile)) {
$imageData=parse_ini_file($textFile);
echo '<h1>'.$imageData['title'].'</h1><h2>'.$imageData['class'].'</h2>'.$imageData['discussion'];
}
?>
</div>
<div class="zoneimage">
<img 
src="<?php echo $img; ?>" 
alt="<?php echo $img; ?>"
style="<?php
$a=getimagesize($img);
$height=560; // change this if the page layout is adjusted
$b=($height-$a[1])/2;
echo "position:relative;top:".$b."px;";
?>"
/>
</div>
</div>
<?php
   $firstPreview=false;
   }
?>
<?php
}
?>
</div> <!-- close zone div -->
</div> <!-- close frame div -->
</body>
</html>

