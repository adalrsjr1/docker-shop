#!/bin/bash

KUBECTL='/usr/local/kubernetes/cluster/kubectl.sh'
KUBELETE='/usr/local/kubernetes/hack/local-up-cluster.sh'

run () {

	echo -e "Running MICROSERVICE Products...\n"
	$KUBECTL run products --image=adalrsjr1/products --port=8080
	$KUBECTL expose deployment products --type="LoadBalancer"
	
	echo -e  "\nRunning MICROSERVICE Profiles...\n"
	$KUBECTL run profiles --image=adalrsjr1/profiles --port=8090
	$KUBECTL expose deployment profiles --type="LoadBalancer"

	echo -e  "\nRunning MICROSERVICE Match...\n"
	$KUBECTL run match --image=adalrsjr1/match --port=8100
	$KUBECTL expose deployment match --type="LoadBalancer"

	echo -e  "\nRunning MICROSERVICE Frontend...\n"
	$KUBECTL run frontend --image=adalrsjr1/frontend --port=8110
	# must set the static ip to --external-ip
	$KUBECTL expose deployment frontend --type="LoadBalancer" --external-ip=192.168.201.254

	echo -e "\nWaiting...\n"
	sleep 10s

	# echo -e "\nTesting DNS -- Products calling Profiles\n"
	# $KUBECTL exec $($KUBECTL get pods | grep -i products | awk -F" " '{print $1}') -- curl -i -X GET profiles.default.svc.cluster.local:8090/profiles/public/users/
	
	echo -e "\nALL OK...\n"
	$KUBECTL get services
}

tear_down () {
	echo -e "Removing MICROSERVICES...\n"
	$KUBECTL delete deployments products profiles match frontend
	$KUBECTL delete services products profiles match frontend

}

clean () {
	echo -e "\nRemoving trash\n"
	docker stop $(docker ps -a -q)
	docker rm $(docker ps -a -q)
}

usage () {
	echo -e "\tUsage:"
	echo -e "\t\t$0 <run>  : start microservices"
	echo -e "\t\t$0 <stop> : stop microservices"
	echo -e "\t\t$0 <clean>: clean kubernetes env"
	echo -e "\t\t$0 <kube> : start kubernetes"
}


if [ $# -ne 1  ]; then
	usage
elif [ $1 == "run" ]; then
	run
elif [ $1 == "stop" ]; then
	tear_down
elif [ $1 == "clean" ]; then
	clean
elif [ $1 == "kube" ]; then
	$KUBELETE
else
	usage
fi


