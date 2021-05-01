To create a release zip to place it on to your web server 
open folder "make release" and if your system runs 

:windows
	>> 	start the "windows-release" file

:linux
	>> 	then make sure a current version of python is installed and
		then make the linux-release.sh executable with the command
		"chmod a+x linux-release.sh" if that finished properly type
		"./linux-release"

and a folder 2 layers above with the name "releases" has been created 
containig the realease.zip file. Extract the compressed folder "api"
and place it into your web servers folder.
