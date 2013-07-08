#!/bin/bash

if [ $# -eq 0 ]; then
   echo "no host list !"; exit 1;
fi

cat $1 | dsh -w - "~/get_hwdb_data" | grep -v "No route" | egrep -iv "exit|unknown" | awk -F":" '{print $2}' > ~/doit 2> /dev/null
cat ~/doit
#sh ~/doit
