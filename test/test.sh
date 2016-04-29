#!/bin/bash

run() {
	for ((i=1; i<=$1; i++));do
		# curl -i -X GET "10.0.0.35:8100/match/public/login/Usuario$(echo $(($RANDOM % 10 + 1)) )";
		curl -i -X GET "192.168.201.254:8110/frontend/public/products?user=Usuario$( echo $(( $RANDOM % 10 + 1  ))  )";
	done
}

if [ $1 = "run"  ]; then
	run $2
fi
