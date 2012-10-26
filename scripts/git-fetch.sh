#!/bin/bash
cd $1
git fetch $2 $3
git checkout FETCH_HEAD