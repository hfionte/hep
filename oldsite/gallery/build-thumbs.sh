#!/bin/bash

# full path, but without trailing slash
ImagePath="images"

for i in $ImagePath/*/*
do
   if [ -d $i/originals ]; then
      echo $i
      if [ ! -d $i/thumbs ]; then mkdir $i/thumbs; else rm -f $i/thumbs/*.jpg; fi
      if [ ! -d $i/previews ]; then mkdir $i/previews; else rm -f $i/previews/*.jpg; fi
      cp -p $i/originals/*.jpg $i/thumbs/
      cp -p $i/originals/*.jpg $i/previews/
      /usr/bin/mogrify -resize "150x150>" -quality 60 $i/thumbs/*.jpg
      /usr/bin/mogrify -resize "800X450>" -quality 60 $i/previews/*.jpg
#      /usr/bin/mogrify -resize "1500x1500>" -quality 60 $i/originals/*.jpg
      chmod a+rw $i/thumbs
      chmod a+rw $i/previews
      chmod a+rw $i/thumbs/*.jpg
      chmod a+rw $i/previews/*.jpg
      chmod a+r $i/originals/*.jpg
   fi
done

