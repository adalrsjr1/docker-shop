#!/bin/bash

for i in {1..100}; do
	curl -i -X GET "10.0.0.35:8100/match/public/login/Usuario$(echo $(($RANDOM % 10 + 1)) )";
done

