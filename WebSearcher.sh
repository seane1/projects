#!/bin/bash

for i in 0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29
do
/usr/bin/curl -o log$i http://localhost:8000/Healthcheck/log-viewer.php?facility=$i&date= ;
/bin/grep Voltage log$i -o | grep Voltage -c >> count ;
 
done

