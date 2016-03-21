#!/bin/bash


FILE=/var/www/html/cursoangularjs/Server/api/swap/swap.txt
DIR="/home/jose/Documentos"


if [ -e $FILE ]; then
  while read linea 
  do
     mkdir $DIR/$linea
  done < $FILE
  rm -f $FILE
else
  echo "nada que hacer"
fi

