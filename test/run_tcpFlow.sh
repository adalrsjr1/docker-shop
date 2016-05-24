#!/bin/bash

sudo tcpflow -Ft -c -i docker0 | grep --line-buffered -oE '.* (GET|PUT|DELETE|POST|HEAD) .* HTTP/1.[01]|.* HTTP/1.[01] [0-9]+ [A-Z ]*' > /home/adalrsjr1/system.trace
