var requrl = 'ha1.php';
var openFirst = true;

function createRequest() {
   var req = null;
   try {
      req = new XMLHttpRequest();
   } 
   catch (trymicrosoft) { 
      try {
         req = new ActiveXObject("Msxml2.XMLHTTP");
      } 
      catch (othermicrosoft) {
         try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
         }
            catch (failed) {
               req = null;
         }
      }
   }
   if (req == null) {
      alert("Error creating request object!");
   }
   else {
      return req;
   }
}

function isShowFirst() {
   return ('open' == document.getElementById("sidenav").getElementsByTagName("dl")[0].className?true:false);
}

function addHandlers() {
   var elements = document.getElementsByTagName("img");
   for (var i=0; i<elements.length; i++) {
      if ("thumbs" == elements[i].parentNode.className) {
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
}

function setPreview(el) {
   var imgs = el.parentNode.parentNode.getElementsByTagName("img");
   var s = el.getAttribute("src").replace("thumbs","previews");
   var previewImg = '';
   for (var i=0; i<imgs.length; i++) {
      imgs[i].className="unselected";
      if (s == imgs[i].getAttribute("src")) { previewImg = imgs[i]; }
   }
   el.className="selected";
   previewImg.className="selected";
}

function setPreviewFirst(key) {
   var spans = document.getElementById("frame").getElementsByTagName("span");
   for (var i=0; i<spans.length; i++) {
      if (key == spans[i].firstChild.nodeValue) {
         setPreview(spans[i].parentNode.getElementsByTagName("img")[0]);
         return;
      }
   }
}

function openGalleryList(el) {
   if ("closed" == el.parentNode.className) {
      var elements = document.getElementById("sidenav").getElementsByTagName("dl");
      for (var i=0; i<elements.length; i++) {
         elements[i].className="closed"; 
      }
      el.parentNode.className="open";
   }
}

function sendRequest(request, data, funct) {
alert('sending ' + data);
   var url = requrl + '?' + data;
   request.open("GET", url, true);
   request.onreadystatechange = funct;
   request.send(null);
}

var activateRequest = createRequest();

function addSelected() {
   var request = activateRequest;
   if (request.readyState == 4) {
      if (request.status == 200) {
         var data = request.responseText;
   alert(data);
         var frag = createDocumentFragment();
         frag.addChild(createTextNode(data));
         getElementById('frame').addChild(frag);
      } else {
         var message = request.getResponseHeader("Status");
         if ((message.length == null) || (message.length <= 0)) {
         alert("Error! Request status is " + request.status);
         } 
         else {
         alert(message);
         }
      }
   }
}

function activateImageSet(el) {
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
      if ("activeimgs" == elements[i].className) {
         elements[i].className="inactiveimgs"; 
      }
   }
   // in the image display, activate the selected imageset - if it exists
   var key = el.getElementsByTagName("span")[0].firstChild.nodeValue;
   elements = document.getElementById("frame").getElementsByTagName("span");
   for (var i=0; i<elements.length; i++) {
      if (key == elements[i].firstChild.nodeValue) {
         elements[i].parentNode.parentNode.className="activeimgs"; 
         return key; // return the imageSet key
      }
   }
   // reach this point of the imageset has not been loaded yet
   sendRequest(activateRequest, ('imageset=' + key), addSelected);
}

function setFirstSet(galleryEl) {
   alert("setFirstSet");
   var ddList = galleryEl.parentNode.getElementsByTagName("dd");
   ddList[0].className="open";
   for (var i=1; i<length.ddList; i++) {
      ddList[i].className="closed";
   }
   return ddList[0].firstSibling.firstChild.nodeValue; // return key
}

function previewRandomImage() {
   var i;
// get divs in the frame element
   var thumbdivs = new Array();
   var divs = document.getElementById("frame").getElementsByTagName("div");
// make a list of all the divs that are class thumbs
   for (i=0; i<divs.length; i++) {
      if ("thumbs" == divs[i].className) { thumbdivs.push(divs[i]); }
   }
// make a list of all the imgs
   var imgArray = new Array();
   for (i=0; i<thumbdivs.length; i++) {
      var imgs = thumbdivs[i].getElementsByTagName("img");
      for (j=0; j<imgs.length; j++) {
         imgArray.push(imgs[j]);
      }
   }
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


function startup () {
//   addHandlers();
   if (!isShowFirst()) { previewRandomImage(); }
}

window.onload = startup;

