var CL = "class";

window.onload = startup;

function startup () {
   browserClassTest();
   addHandlers();
   if (!isShowFirst()) { previewRandomImage(); }
}

function isShowFirst() {
   return ('open' == document.getElementById("sidenav").getElementsByTagName("dl")[0].getAttribute(CL)?true:false);
}

function browserClassTest() {
   var el = document.getElementById("header");
   if ("test" != el.getAttribute(CL)) {
      CL = "className";
      if ("test" != el.getAttribute(CL)) {
         alert("class/className test failed");
      }
   }
}

function addHandlers() {
   var elements = document.getElementsByTagName("img");
   for (var i=0; i<elements.length; i++) {
      if ("thumbs" == elements[i].parentNode.getAttribute(CL)) {
         elements[i].onclick = setPreviewThis; 
      }
   }
   var sidenav = document.getElementById("sidenav");
   elements = sidenav.getElementsByTagName("dt");
   for (var i=0; i<elements.length; i++) {
      elements[i].onclick = openGalleryListThis;
   }
   elements = sidenav.getElementsByTagName("dd");
   for (var i=0; i<elements.length; i++) {
      elements[i].onclick = activateImageSetThis;
   }
   document.getElementById("randomize").onclick = previewRandomImage;
}

function setPreviewThis() {setPreview(this);}
function setPreview(el) {
   var imgs = el.parentNode.parentNode.getElementsByTagName("img");
   var previewImg = imgs[imgs.length - 1]
   var s = el.getAttribute("src");
   previewImg.setAttribute("src",s.replace("thumbs","previews"));
   previewImg.parentNode.setAttribute("href",s.replace("thumbs","originals"));
   imgs = el.parentNode.getElementsByTagName("img");
   for (var i=0; i<imgs.length; i++) {
      imgs[i].setAttribute(CL,"unselected");
   }
   el.setAttribute(CL,"selected");
}

function openGalleryListThis() {openGalleryList(this);}
function openGalleryList(el) {
   if ("closed" == el.parentNode.getAttribute(CL)) {
      var elements = document.getElementById("sidenav").getElementsByTagName("dl");
      for (var i=0; i<elements.length; i++) {
         if ("open" == elements[i].getAttribute(CL)) {
            elements[i].setAttribute(CL,"closed"); 
         }
      }
      el.parentNode.setAttribute(CL,"open");
   }
}

function activateImageSetThis() {activateImageSet(this);}
function activateImageSet(el) {
   var elements = document.getElementById("sidenav").getElementsByTagName("dd");
   for (var i=0; i<elements.length; i++) {
      elements[i].setAttribute(CL,"closed"); 
   }
   el.setAttribute(CL,"open");
   elements = document.getElementById("frame").getElementsByTagName("div");
   for (var i=0; i<elements.length; i++) {
      if ("activeimgs" == elements[i].getAttribute(CL)) {
         elements[i].setAttribute(CL,"inactiveimgs"); 
      }
   }
   var key = el.getElementsByTagName("span")[0].firstChild.nodeValue;
   elements = document.getElementById("frame").getElementsByTagName("span");
   for (var i=0; i<elements.length; i++) {
      if (key == elements[i].firstChild.nodeValue) {
         elements[i].parentNode.parentNode.setAttribute(CL,"activeimgs"); 
      }
   }
}

function previewRandomImage() {
   var i;
// get divs in the frame element
   var thumbdivs = new Array();
   var divs = document.getElementById("frame").getElementsByTagName("div");
// make a list of all the divs that are class thumbs
   for (i=0; i<divs.length; i++) {
      if ("thumbs" == divs[i].getAttribute(CL)) { thumbdivs.push(divs[i]); }
   }
// make a list of all the imgs
   var imgArray = new Array();
   for (i=0; i<thumbdivs.length; i++) {
      var imgs = thumbdivs[i].getElementsByTagName("img");
      for (j=0; j<imgs.length; j++) {
         imgArray.push(imgs[j]);
      }
   }
// pick a random img from the list if imgs
// Returns a random integer between min and max
// Using Math.round() will give you a non-uniform distribution!
   var r = getRandomInt(0, (imgArray.length - 1));
   var img = imgArray[r];
   var src = img.getAttribute("src");
// set galList and imgSet
   var imgSet;
   var imageSetPath = img.parentNode.parentNode.getElementsByTagName("span")[0].firstChild.nodeValue;
   var spans = document.getElementById("sidenav").getElementsByTagName("span");
   for (i=0; i<spans.length; i++) {
      if (imageSetPath == spans[i].firstChild.nodeValue) {
         imgSet = spans[i].parentNode;
         break;
      }
   }
   var galList = imgSet.parentNode.getElementsByTagName("dt")[0];
   openGalleryList(galList);
   activateImageSet(imgSet);
   setPreview(img);
}

function getRandomInt(min, max) {
   return Math.floor(Math.random() * (max - min + 1)) + min;
}

