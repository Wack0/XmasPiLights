# treeclient
Client-side application to use `treeapi` and execute `dopin`.

Change the `treeapi` constant in `treeclient.d` to point to your own `treeapi` instance.

Compile using a command line like:

    gdc-4.8 -otreeclient treeclient.d -lcurl

This assumes that gdc is installed under gdc-4.8 (which it is on Raspbian), and that gdc and libcurl-dev are both installed.  
You could also cross compile if you wished. Compiling `treeclient` directly on Raspberry Pis takes a few minutes.

After compiling, you can just use `./treeclient` to run.