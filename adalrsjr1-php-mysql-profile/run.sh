#!/bin/bash

if [ "$#" -ne 4 ]; then
	echo "Usage: $0 <container name> <apache port> <mysql port> <volume name>"
    exit 1
fi


if [ "$3" -eq 0 ]; then
	docker run -it --rm --name $1 -p $2:8090 -v /data/mysql/$4:/data adalrsjr1/profiles
else 
	docker run -it --rm --name $1 -p $2:8090 -p $3:3306 -v /data/mysql/$4:/data adalrsjr1/profiles
fi
