#!/bin/bash

if [ -d "../pyversion/python394" ]; then
	../pyversion/python394/python ../pyscript/release.py	
else
	wget wget https://www.python.org/ftp/python/3.9.4/Python-3.9.4.tar.xz
	tar -xf Python-3.9.4.tar.xz
	rm ./Python-3.9.4.tar.xz
	cp -r ./Python-3.9.4 ../pyversion/python394
	rm -r ./Python-3.9.4
	cd ../pyversion/python394/
	./configure; make
	../pyversion/python394/python ../pyscript/release.py
fi
