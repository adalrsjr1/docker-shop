#!/bin/bash

# sudo tcpflow -Ft -c -i docker0 | grep --line-buffered -oE '.* (GET|PUT|DELETE|POST|HEAD) .* HTTP/1.[01]|.* HTTP/1.[01] [0-9]+ [A-Z ]*' > /home/adalrsjr1/system.trace

sudo tcpflow -Ft -c -i docker0 | awk '{if($0 ~ /.* (GET|PUT|DELETE|POST|HEAD) .* HTTP\/1.[01]|.* HTTP\/1.[01] [0-9]+ [A-Z ]*/) print ### $0; if(bash ~ /X-Unique-Id: *[a-z0-9]{32}/) print $0; fflush(stdout);}' > /home/adalrsjr1/system.trace2
