#!/bin/bash

ImagePath="."
pushd .  >/dev/null
if [ ! -d $ImagePath/previews ]; then mkdir $ImagePath/previews; fi
cd $ImagePath/originals
for gal in *
do
  if [ -d $gal ]; then
    echo Gallery: $gal ---------------
    if [ ! -d ../previews/$gal ]; then mkdir ../previews/$gal; fi
    pushd .  >/dev/null
    cd $gal
    for iset in *
    do
      if [ -d $iset ]; then
        echo "   ImageSet:" $iset 
        if [ ! -d ../../previews/$gal/$iset ]; 
          then mkdir ../../previews/$gal/$iset; 
          else 
            rm -f ../../previews/$gal/$iset/*.jpg; 
            rm -f ../../previews/$gal/$iset/*.txt; 
        fi
        pushd . >/dev/null
        cd $iset 
        if [ -f *.txt ]; then cp *.txt ../../../previews/$gal/$iset/; fi
        for img in *.jpg
        do
          echo "      " $img
          convert -resize "526X526>" -quality 60 $img ../../../previews/$gal/$iset/$img
        done
        popd >/dev/null
      fi
    done
    popd >/dev/null
  fi
done
popd >/dev/null
chmod a+r $ImagePath/previews
chmod a+r $ImagePath/previews/*/*/*.jpg
chmod a+r $ImagePath/previews/*/*/*.txt

