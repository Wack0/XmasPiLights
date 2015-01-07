# dopin  
Client-side code to use the Energenie GPIO device on a Raspberry Pi. Intended to be setuid.

Uses [wiringPi](http://wiringpi.com/).

To build and install:

    cd wiringPi
    ./build
    cd ..
    gcc -odopin dopin.c -lwiringPi
    sudo cp dopin /usr/bin/dopin
    sudo chown root:root /usr/bin/dopin
    sudo chmod 4755 /usr/bin/dopin