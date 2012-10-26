#!/bin/bash
exec ssh -o StrictHostKeyChecking=no -i $1 -p 29418 $2 gerrit query --format=JSON --current-patch-set change:$3