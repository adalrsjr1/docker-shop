#!/bin/bash

if [ "$#" -ne 4 ]; then
	echo "Usage: $0 <container name> <apache port> <mysql port> <volume name>"
    exit 1
fi

docker run -it --rm --name $1 -p $2:80 -p $3:3306 -v /data/mysql/$4:/data adalrsjr1/products
