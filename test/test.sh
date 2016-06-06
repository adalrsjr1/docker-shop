#!/bin/bash

# http://stackoverflow.com/questions/15060762/httperf-command-options

evaluate() {
	val=$(( $1 * 60 ))
	httperf --hog --server 192.168.201.254 --port 8110 --uri /frontend/public/products?user=Usuario5 --rate $1 --num-conn $val  --num-call 1 --timeout 5
}

run() {
	for ((i=1; i<=$1; i++));do
		# curl -i -X GET "10.0.0.35:8100/match/public/login/Usuario$(echo $(($RANDOM % 10 + 1)) )";
		v=$(echo $(($RANDOM % 10 + 1)))
		u="Usuario"
		echo $u$v
		curl -i --header "X-Unique-Id: $(echo -n $RANDOM | md5sum | awk '{print$1}')" -X GET "192.168.201.254:8110/frontend/public/products?user=Usuario"$(echo $v)
	done
}

usage() {
	echo "./test.sh run <number_requests>"
	echo "./test.sh evaluate <rate_of_requests>"
}

if [ $# -ne 2  ]; then
	usage
elif [ $1 = "run"  ]; then
	run $2
elif [ $1 = "evaluate" ]; then
	evaluate $2
else
	usage
fi
