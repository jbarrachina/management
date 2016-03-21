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

clave=$(cat .clave)
mysqldump --replace --skip-add-drop-table --no-create-info --skip-triggers -u root -p$clave $database $table --where=$condition > $table.sql
