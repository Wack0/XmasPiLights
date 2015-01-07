# XmasPiLights client-side code

The following projects are included in this directory:  
*dopin* - an application that uses the GPIO pins to turn an Energenie socket on and off. Intended to be setuid as root, so an application using Energenie sockets does not have to be ran as root.  
*treeclient* - the application that contacts `treeapi` and uses `dopin` based on the output of `treeapi`.