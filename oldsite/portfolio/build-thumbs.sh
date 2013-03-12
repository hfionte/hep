#!/bin/bash

ImagePath="."

if [ ! -d $ImagePath/thumbs ]; then mkdir $ImagePath/thumbs; fi
if [ ! -d $ImagePath/previews ]; then mkdir $ImagePath/previews; fi
pushd .  >/dev/null
cd $ImagePath/originals
for gal in *
do
  if [ -d $gal ]; then
    echo Gallery: $gal ---------------
    if [ ! -d ../thumbs/$gal ]; then mkdir ../thumbs/$gal; fi
    if [ ! -d ../previews/$gal ]; then mkdir ../previews/$gal; fi
    pushd .  >/dev/null
    cd $gal
    for iset in *
    do
      if [ -d $iset ]; then
        echo "   ImageSet:" $iset 
        if [ ! -d ../../thumbs/$gal/$iset ]; 
          then mkdir ../../thumbs/$gal/$iset; 
          else rm -f ../../thumbs/$gal/$iset/*.jpg; 
        fi
        if [ ! -d ../../previews/$gal/$iset ]; 
          then mkdir ../../previews/$gal/$iset; 
          else rm -f ../../previews/$gal/$iset/*.jpg; 
        fi
        pushd . >/dev/null
        cd $iset
        for img in *.jpg
        do
          echo "      " $img
          convert -thumbnail "120X120>" -quality 60 $img ../../../thumbs/$gal/$iset/$img
          convert -thumbnail "870X490>" -quality 60 $img ../../../previews/$gal/$iset/$img
        done
        popd >/dev/null
      fi
    done
    popd >/dev/null
  fi
done
popd >/dev/null
chmod a+rw $ImagePath/thumbs
chmod a+rw $ImagePath/previews
chmod a+rw $ImagePath/thumbs/*/*/*.jpg
chmod a+rw $ImagePath/previews/*/*/*.jpg

