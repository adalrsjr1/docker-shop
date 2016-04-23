#!/bin/bash

# need add a '&' at begining of perfis.txt file

awk < $1 ' 
BEGIN {
	RS="&";
	FS="\n+";
}

function splitVector(str) {
	n2=split(str,arr,",")
	nstr = ""
	for(j=1;j<n2;j++)
		nstr = nstr "\""arr[j]"\","
	nstr = nstr "\""arr[j]"\""
	return nstr
}

function splitArrComma(str) {
	split(str,arr,":")
	return "\""arr[1]"\""":["splitVector(arr[2])"],"
}

function splitArr(str) {
	split(str,arr,":")
	return "\""arr[1]"\""":["splitVector(arr[2])"]"
}


function toJson(str) {
	n=split(str,a,"#")
	s = "{\""a[1]"\":"
	
	aux = ""
	for(i=2; i<n; i++) {
		aux = aux splitArrComma(a[i])
	}
	aux = aux splitArr(a[i])
	
	s = s"{" aux "}}"	
	return s
}

{
	if($2 != "")
		print "{\""$2"\":"toJson($3)"}"

}' > $2


