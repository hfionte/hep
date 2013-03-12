var onSidenav = 0;  // current 'on' onoff node in sidenav (button)
var onZone = 0;    // current 'on' onoff node in zone (div)
var openISet = 0;  // current imageSet (dd)
var openGal = 0;   // current gallery (dl)
var stampArray = new Array(); // used by getIndex() and setIndex()
var stampIndex = 0; // used by getIndex() and setIndex()
var timedRandom = false;
var timedNext = false;
var delay = 3000;

function clickStamp(el) {
   setPreview(el);
}

function clickImageSet(el) {
   setImageSet(el.parentNode);
   setPreview(openISet.getElementsByTagName("button")[0]);
}

function clickGallery(el) {
   setGallery(el.parentNode);
   setFirstImageSet(el.parentNode);
}

// for each set function the primary argument is the element that controls visibility (on/off or open/closed)

function setPreview(onoffel) {
   onZone.className="off";
   onSidenav.className="off";
   onoffel.className="on";
   onSidenav = onoffel;
   onZone = zoneFromSidenav(onSidenav);
   onZone.className = "on";
}

function zoneFromSidenav(onoffel) {
   var key = onoffel.previousSibling.firstChild.nodeValue;
   var elements = document.getElementById("zone").getElementsByTagName("span");
   for (var i=0; i<elements.length; i++) {
      if (key == elements[i].firstChild.nodeValue) {
         return elements[i].parentNode;
      }
   }
}

function setGallery(el) {
   if (el.className == "closed") {
      openGal.className="closed";
      openGal=el;
      el.className="open";
   }
}

function setImageSet(el) {
   if (el.className == "closed") {
      openISet.className="closed";
      openISet=el;
      el.className="open";
   }
}

function setFirstImageSet(galleryEl) {
   setImageSet(galleryEl.getElementsByTagName("dd")[0]);
   setPreview(openISet.getElementsByTagName("button")[0]);
}

function getIndex() {
   if (onSidenav == stampArray[stampIndex]){return stampIndex;}
   for (var i=0; i<stampArray.length; i++) {
      if (onSidenav == stampArray[i]) {
         stampIndex = i;
         return stampIndex;
      }
   }
}

function setIndex(index) {
   if (index >= stampArray.length){index=0;}
   if (index < 0){index=stampArray.length - 1;}
   stampIndex = index;
   return stampIndex;
}

function jumpToImage(stamp) {
   setGallery(stamp.parentNode.parentNode);
   setImageSet(stamp.parentNode);
   setPreview(stamp);
}

// controls, etc.

function previousImage() {
   var index = getIndex() - 1;
   index = setIndex(index);
   jumpToImage(stampArray[index]);
}

function nextImage() {
   var index = getIndex() + 1;
   index = setIndex(index);
   jumpToImage(stampArray[index]);
}

function randomImage() {
   var r = getRandomInt(0, (stampArray.length - 1));
   jumpToImage(stampArray[r]);
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

function getRandomInt(min, max) {
   return Math.floor(Math.random() * (max - min + 1)) + min;
}

// startup activities

function startup () {
   stampArray = document.getElementById('sidenav').getElementsByTagName('button');
   openGal = document.getElementById('sidenav').getElementsByTagName('dl')[0];
   openISet = openGal.getElementsByTagName('dd')[0];
   onSidenav = openISet.getElementsByTagName('button')[0];
   onZone = zoneFromSidenav(onSidenav);
}

