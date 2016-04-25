#!/bin/bash

KUBECTL='/usr/local/home/kubernetes/cluster/kubectl.sh'
KUBELETE='/usr/local/home/kubernetes/hack/local-up-cluster.sh'

run () {

	echo -e "Running MICROSERVICE Products...\n"
	$KUBECTL run products --image=adalrsjr1/products --port=8080
	$KUBECTL expose deployment products --type="LoadBalancer"
	
	echo -e  "\nRunning MICROSERVICE Profiles...\n"
	$KUBECTL run profiles --image=adalrsjr1/profiles --port=8090
	$KUBECTL expose deployment profiles --type="LoadBalancer"

	echo -e "\nWaiting...\n"
	sleep 30s

	echo -e "\nTesting DNS -- Products calling Profiles\n"
	$KUBECTL exec $($KUBECTL get pods | grep -i products | awk -F" " '{print $1}') -- curl -i -X GET profiles.default.svc.cluster.local:8090/profiles/public/users/
	
	echo -e "\nTesting DNS -- Profiles calling Products\n"
	$KUBECTL exec $($KUBECTL get pods | grep -i profiles | awk -F" " '{print $1}') -- curl -i -X GET products.default.svc.cluster.local:8080/products/public/products/

	echo -e "\nALL OK...\n"
}

tear_down () {
	echo -e "Removing MICROSERVICES...\n"
	$KUBECTL delete deployments products profiles
	$KUBECTL delete services products profiles
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


