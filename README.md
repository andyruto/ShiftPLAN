# shiftPLAN
This is a small shift planning app. This app provides the ability to
plan shifts for all workers, assigning responsible persons for one worker
and sending notifications to all users after changing the shift plan.
The backend is also provided in this repository. Backend is written in PHP and
the Desktop app in Java with JavaFX. This project is founded as university project.

## Contributors
Andreas G. | Moritz W. | Sascha W. | Maximilian T.     
---------- | --------- | --------- | -------- 
Backend | n/a | n/a | Backend

## Features
* viewing the own current shift
* planning shifts and responsible persons
* managing new workers and users
* sending notifications on shift plan changes
* (optional) export to different document types
* (optional) sharing functionality
* (optional) printing functionality

## Syntax rules
* classes: PascalCase
* methods, variables: camelCase
* const: CAPITAL_LETTERS
* if(condition) {<br/>
	...<br/>
  } else if(condition) {<br/>
	...<br/>
  } else {<br/>
	...<br/>
  }
* switch(object) {<br/>
	case 1:<br/>
		statement;<br/>
		break;<br/>
  }<br/>
* ToDo-comments: //ToDo: The remaining things to to.

## Comments
Every file (class, pom.xml, .gitignore) should begin with a file description comment. 
It should contain the author, last edit timestamp and the last editors name.

## Git
* Methods with open changes or implementations should be commented before pushing 
the last commit.
* For every new functionality there should be a new branch to work on. (feature branch organization)

## Package structure
Package structure is still in discussion. This is just a template structure tree. It will be edited
afterwards.
<pre>
org.shiftPLAN.shiftPLANDesktop
|
|---Graphics
|   |
|   |---Interfaces
|   |---Enums
|
|---Helper
|   |
|   |---Grapics
|   |---Logics
|       |
|       |---Actors
|       |---Stages
|
|---Logics
    |
    |---Interfaces
    |---Enum
    |---Actors
    |   |
    |   |---Interfaces
    |   |---Enum
    |
    |---Stages
        |
        |---Interfaces
        |---Enum
</pre>

## UML and Plan diagrams
The UML and Plan diagrams where made with modelio v4.0. An open source tool for creating software
architecture diagrams and more. <br/>
<br/>
Link: https://www.modelio.org/