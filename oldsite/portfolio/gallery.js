var numImages = 0;
var numImageSets = 0;
var numGalleries = 0;
var loadedThumbs = 0;
var loadedPreviews = 0;
var loadedFirstPreviews = 0;
var emptySrc = false;
var imageArray = new Array(); // images: thumb img el, IS element, gallery el
var imageSetArray = new Array(); // imagesets: key, IS element, gallery el
var timedRandom = false;
var timedNext = false;
var delay = 3000;

function setFullSize(el) {
   var src = el.src.replace("previews","originals");
   var original = document.getElementById('original');
   original.getElementsByTagName('img')[0].src=src;
   document.getElementById('notoriginal').className = 'off';
   original.className = 'on';
}

function returnFromFullSize() {
   document.getElementById('notoriginal').className = 'on';
   document.getElementById('original').className = 'off';
   document.getElementById('original').getElementsByTagName('img')[0].src='';
}

function setPreview(el) {
   // clear the last selection, whatever it might have been
   var imgs = document.getElementById('frame').getElementsByTagName('img');
      for (var i=0;i<imgs.length;i++) {imgs[i].className='unselected';}
   var s = el.alt.replace("thumbs","previews");
   // set the right preview
   imgs = el.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName("img");
   for (var i=0;i<imgs.length;i++) {
      if (s == imgs[i].alt) {imgs[i].className='selected';}
   }
   // set the thumb that was clicked
   el.className="selected";
}

function setPreviewFirst(el) {
   setPreview(el.parentNode.getElementsByTagName("img")[0]);
}

function setGallery(el, setFirst) {
   if ("closed" == el.parentNode.className) {
      var elements = document.getElementById("sidenav").getElementsByTagName("dl");
      for (var i=0; i<elements.length; i++) {
         elements[i].className="closed"; 
      }
      el.parentNode.className="open";
      if (setFirst){setImageSetFirst(el);}
   }
}

function setGalleryAndFirst(el) {
   setGallery(el,true);
}

function setImageSet(el, setFirst) {
   // in sidenav, mark all imagesets closed
   var elements = document.getElementById("sidenav").getElementsByTagName("dd");
   for (var i=0; i<elements.length; i++) {
      elements[i].className="closed"; 
   }
   // in sidenav, mark the selected imageset open
   el.className="open";
   // in the image display mark all imagesets inactive
   elements = document.getElementById("frame").getElementsByTagName("div");
   for (var i=0; i<elements.length; i++) {
      if (elements[i].className=='activeimgs') {elements[i].className="inactiveimgs";}
   }
   if (setFirst) {
   // in the image display mark all imgs unselected
      elements = document.getElementById("frame").getElementsByTagName("img");
      for (var i=0; i<elements.length; i++) {
         elements[i].className="unselected"; 
      }
   }
   // in the image display, activate the selected imageset - if it exists
   var key = el.getElementsByTagName("span")[0].firstChild.nodeValue;
   elements = document.getElementById("frame").getElementsByTagName("span");
   for (var i=0; i<elements.length; i++) {
      if (key == elements[i].firstChild.nodeValue) {
         var imgSetEl = elements[i].parentNode;
         imgSetEl.parentNode.className="activeimgs"; 
         addImageSetImages(imgSetEl);
         if (setFirst) {setPreviewFirst(imgSetEl);}
         return;
      }
   }
}

function setImageSetAndFirst(el) {
   setImageSet(el,true);
}

function setImageSetFirst(galleryEl) {
   var ddList = galleryEl.parentNode.getElementsByTagName("dd");
   setImageSetAndFirst(ddList[0]);
}

function addImageSetImages(el) {
   var imgs = el.getElementsByTagName('img');
   for (var i=0;i<imgs.length;i++) {
      addImage(imgs[i]);
   }
}

function onThumbLoad() {
   loadedThumbs++;
   if (loadedThumbs == numImages) {
      addFirstPreviews();
   }
}

function onPreviewLoad() {
   loadedPreviews++;
}

function onFirstPreviewLoad() {
   loadedPreviews++;
   loadedFirstPreviews++;
   if (loadedFirstPreviews == numImageSets) {
      addAllPreviews();
   }
}

function addImage(img) {
   if (img.src == emptySrc) {img.src = img.alt;}
}

function addAllThumbs() {
   var divs = document.getElementById('frame').getElementsByTagName('div');
   for (var div=0;div<divs.length;div++) {
      if ('thumbs' == divs[div].className) {
         var imgs = divs[div].getElementsByTagName('img');
         for (var i=0;i<imgs.length;i++) {
            addImage(imgs[i]);
         }
      }
   }
}

function addFirstPreviews() {
   var divs = document.getElementById('frame').getElementsByTagName('div');
   for (var div=0;div<divs.length;div++) {
      if ('previews' == divs[div].className) {
         var imgs = divs[div].getElementsByTagName('img');
         addImage(imgs[0]);
      }
   }
}

function addAllPreviews() {
   var divs = document.getElementById('frame').getElementsByTagName('div');
   for (var div=0;div<divs.length;div++) {
      if ('previews' == divs[div].className) {
         var imgs = divs[div].getElementsByTagName('img');
         for (var i=0;i<imgs.length;i++) {
            addImage(imgs[i]);
         }
      }
   }
}

function jumpToImage(data) {
   setPreview(data[0]);
   setImageSet(data[1]);
   setGallery(data[2]);
}

// controls, etc.

function headerClick() {
   alert('galleries: '+numGalleries+'\nimagesets: '+numImageSets+'\nthumbs: '+loadedThumbs+' / '+numImages+'\ninitial previews: '+loadedFirstPreviews+' / '+numImageSets+'\npreviews: '+loadedPreviews+' / '+numImages);
}

function previousImage() {
   for (var i=0;i<imageArray.length;i++) {
      if ('selected' == imageArray[i][0].className) {
         if (i==0){return;}
         imageArray[i][0].className = 'unselected';
         jumpToImage(imageArray[i-1]);
         return;
      }
   }
}

function nextImage() {
   for (var i=0;i<imageArray.length;i++) {
      if ('selected' == imageArray[i][0].className) {
         if (i==imageArray.length-1){return;}
         imageArray[i][0].className = 'unselected';
         jumpToImage(imageArray[i+1]);
         return;
      }
   }
}

function showContinuous() {
   el = document.getElementById('showcontinuous');
   if (timedNext) {
      el.className = 'button';
      clearTimeout(timedNext); 
      timedNext = false;
   } else {
      el.className = 'buttonON';
      showContLoop();
   }
}

function showContLoop() {
   nextImage();
   timedNext = setTimeout("showContLoop();",delay);
}

function showRandom(el) {
   el = document.getElementById('showrandom');
   if (timedRandom) {
      el.className = 'button';
      clearTimeout(timedRandom); 
      timedRandom = false;
   } else {
      el.className = 'buttonON';
      showRandomLoop();
   }
}

function showRandomLoop() {
      randomImage();
      timedRandom = setTimeout("showRandomLoop();",delay);
}

function randomImage() {
   var r = getRandomInt(0, (imageArray.length - 1));
   jumpToImage(imageArray[r]);
}

function getRandomInt(min, max) {
   return Math.floor(Math.random() * (max - min + 1)) + min;
}

// startup activities

function countThings() {
   emptySrc = document.getElementById('testimg').src;
   numImages = imageArray.length;
   numImageSets = document.getElementById('sidenav').getElementsByTagName('dd').length;
   numGalleries = document.getElementById('sidenav').getElementsByTagName('dt').length;
}

function iSetToGallery(iSetEl) {
   return iSetEl.parentNode.getElementsByTagName('dt')[0];
}

function keyToImageSet(key) {
   var spans = document.getElementById("sidenav").getElementsByTagName("span");
   for (i=0; i<spans.length; i++) {
      if (key == spans[i].firstChild.nodeValue) {
         return spans[i].parentNode;
      }
   }
}

function buildImageSetArray() {
   var key = false;
   var gallery = false;
   var divs = document.getElementById('sidenav').getElementsByTagName('dd');
   for (var i=0;i<divs.length;i++) {
      key = divs[i].getElementsByTagName('span')[0].firstChild.nodeValue;
      gallery = divs[i].parentNode.getElementsByTagName('dt')[0];
      imageSetArray.push(new Array(key, divs[i], gallery));
   }
}

function buildImageArray() {
   var thumbdivs = new Array();
   var divs = document.getElementById("frame").getElementsByTagName("div");
// make a list of all the divs that are class thumbs
   for (var i=0; i<divs.length; i++) {
      if ("thumbs" == divs[i].className) { thumbdivs.push(divs[i]); }
   }
// make a list of all the imgs
   for (i=0; i<thumbdivs.length; i++) {
      var imgs = thumbdivs[i].getElementsByTagName("img");
      for (j=0; j<imgs.length; j++) {
         var img = imgs[j];
         var key = img.parentNode.parentNode.getElementsByTagName('span')[0].firstChild.nodeValue;
         var iSet = keyToImageSet(key);
         var gallery = iSetToGallery(iSet);
         imageArray.push(new Array(img, iSet, gallery));
      }
   }
}

/*
function setImgOnLoads() {
   var thumbdivs = new Array();
   var previewdivs = new Array();
   var imgs = false;
   var divs = document.getElementById('frame').getElementsByTagName('div');
   for (var i=0;i<divs.length; i++) {
      if (divs[i].className == 'thumbs') { thumbdivs.push(divs[i]); }
      if (divs[i].className == 'previews') { previewdivs.push(divs[i]); }
   }
   for (var div=0;div<thumbdivs.length;div++) {
      imgs = thumbdivs[div].getElementsByTagName('img');
      for (var i=0;i<imgs.length; i++) {
         imgs[i].setAttribute('onload','onThumbLoad();');
      }
   }
   for (var div=0;div<previewdivs.length;div++) {
      imgs = previewdivs[div].getElementsByTagName('img');
      for (var i=0;i<imgs.length; i++) {
         imgs[i].setAttribute('onload',((i==0)?'onFirstPreviewLoad()':'onPreviewLoad();'));
      }
   }
}
*/

function startup () {
//   setImgOnLoads();
   buildImageSetArray();
   buildImageArray();
   countThings();
   addAllThumbs();
}

