<?php
require_once ('hinc1.php');

$list = $_REQUEST['list'];
$key = $_REQUEST['key'];

if ($list == 'keys') {
   foreach (array_keys($imageSetList) as $k) {
   echo ($k."<br>\n");
   }
}

if ($iS = $imageSetList[$key]) {
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

