<?php
require_once ('inc.php');
?>
<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo strip_tags($configData['title']); ?></title>
  <link type="text/css" rel="stylesheet" href="gallery.css" />
  <script type="text/javascript" src="gallery.js" > </script>
</head>
<body onload="startup();">
<div id="notoriginal" class="on">
<div id="topbar"></div>
<div id="header" class="test" onclick="headerClick();">
   <h1><?php echo $configData['title']; ?></h1>
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
<div id="sidenav">
<?php
$firstGallery=true;
$firstKey=false; //first imageset in first gallery
foreach (array_keys($galleryList) as $k) {
?>
<dl class="<?php echo($firstGallery?'open':'closed'); ?>">
   <dt onclick="setGalleryAndFirst(this);">
      <?php echo $galleryList[$k]['title'];?>
   </dt>
<?php
   foreach ($imageSetList as $iS) {
      if ($k == $iS['galleryDirectory']) {
?>
   <dd class="<?php echo(!$firstKey?'open':'closed'); ?>" onclick="setImageSetAndFirst(this);">
   <span class="key"><?php echo $iS['imageSetPath'];?></span>
   <?php echo $iS['title'];?>
   </dd>
<?php
         if (!$firstKey) {$firstKey=$iS['imageSetPath'];}
      }
   }
?>
</dl>
<?php
   $firstGallery=false;
}
?>
</div>
<div id="frame">
<?php
foreach ($imageSetList as $iS) {
// imageset wrapper
   $firstIS=(($firstKey==$iS['imageSetPath'])?true:false);
?>
<div class="<?php echo($firstIS?'activeimgs':'inactiveimgs'); ?>"><div class="imageset"><span class="key"><?php echo $iS['imageSetPath']; ?></span>
      <div class="thumbs">
<?php
   // thumbs
   $firstImgName=($firstIS?false:true);
   foreach ($iS['imageSetFiles'] as $img) {
      $alt=str_replace('originals','thumbs',$img);
      $thumb=($firstIS?$alt:'');
?>
         <img 
class="<?php echo(!$firstImgName?'selected':'unselected'); ?>" 
src="<?php echo $thumb; ?>" 
alt="<?php echo $alt; ?>" 
onclick="setPreview(this);" 
onload="onThumbLoad();" />
<?php
      if (!$firstImgName) {$firstImgName=$img;}
   }
?>
      </div>
      <div class="previews">
<?php
   // previews
   $firstPreview=true;
   foreach ($iS['imageSetFiles'] as $img) {
      $alt=str_replace('originals','previews',$img);
      $preview=($firstIS?$alt:'');
?>
            <img 
class="<?php echo(($img==$firstImgName)?'selected':'unselected'); ?>" src="<?php echo $preview; ?>" 
alt="<?php echo $alt; ?>" 
onclick="setFullSize(this);" 
onload="<?php echo($firstPreview?'onFirstPreviewLoad();':'onPreviewLoad();') ?>" />
<?php
   $firstPreview=false;
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
</div>
<div id="original" class="off" onclick="returnFromFullSize();"><img src="" alt="full size image" /></div>
<div style="display:none;" ><img id="testimg" src="" alt="to test returned value of src" /></div>
</body>
</html>

