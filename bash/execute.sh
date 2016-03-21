#!/bin/bash

die() {
        echo "$1" >&2
        exit 1
}

usage(){
        die "Usage: $0 <DATABASE> <TABLE> <CONDITION>"
}

# test data
database="$1"
table="$2"
condition="$3"

[ "$database" ] && [ "$table" ] && [ "$condition" ] || usage

gestion=$(cat .gestion_passwd)

scp .clave depinf@10.10.0.250:/home/depinf
ssh depinf@10.10.0.250 'bash -s' < import.sh $database $table $condition
rsync -e ssh --remove-source-files depinf@10.10.0.250:/home/depinf/$table.sql /home/depinf
ssh depinf@10.10.0.250 "rm .clave"
mysql -u gestion -p$gestion gestion < $table.sql
