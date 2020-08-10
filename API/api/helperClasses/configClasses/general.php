<?php
    /**
     * general.php
     * 
     * PHP file containing functions to fill an validate configs.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    abstract class General{

        //Function to fill the paramters with values
        //@param $key: Key name
        //@param $value: Value of paramter
        public function putInValue(string $key, $value){
            if(property_exists($this, $key)){
                $class = new ReflectionClass(get_class($this));
                $property = $class->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($this, $value);
                $property->setAccessible(false);
                $property = null;
                $class = null;
            } else{
                //Throwing exception if key was not found in object
                throw new InvalidArgumentException($key." doesn't exist in ".get_class($this));
            }
        }

        //Function to get value out of specific object
        //@param $key: Key name
        //@return: property value
        public function getValue(string $key){
            if(property_exists($this, $key)){
                $tmpValue = General::accessProperty($this, $key);

                if(!(empty($tmpValue))){
                    //Returning value if not empty
                    return $tmpValue;
                } else{
                    $tmpValue = null;
                    $matches = null;
                    preg_match_all('/([A-Z]?[a-z]+)/', $key, $matches, PREG_SET_ORDER, 0); //spliting the key name with regex
                    $defaultConstBuilder = strtoupper($matches[0][1]); //converting first regex match to upper case

                    //Going through regex matches and appending them in upper case to $defaultConstBuilder
                    foreach($matches as $index => $match){
                        if($index<1){
                            continue;  
                        } 
                        $defaultConstBuilder .= '_'.strtoupper($match[1]);
                    }
                    return constant(get_class($this)."::".$defaultConstBuilder); //building the constant accessor. example: (class::CONSTANT)
                }
            } else{
                //Throwing exception if key was not found in object
                throw new InvalidArgumentException($key." doesn't exist in ".get_class($this));
            }
        }

        //Function to access a private property properly
        //@param $ref: reference to object
        //@param $name: property key name
        //@return: value of private property
        private static function accessProperty(object $ref, string $name){
            if(property_exists($ref, $name)){
                $class = new ReflectionClass(get_class($ref)); //creating a reflection class of the referenc
                $property = $class->getProperty($name); //storing the reflection property from the reflection class in $property
                $property->setAccessible(true); //setting the accessibility to unrestricted access
                $tmp = $property->getValue($ref);
                $property->setAccessible(false); //setting the accessibility to restricted access
                $property = null;
                $class = null;
                return $tmp;
            } else{
                //Throwing exception if key was not found in object
                throw new InvalidArgumentException($name." doesn't exist in ".get_class($ref));
            }
        }

        //Function to validate the properties values
        abstract public function checkCompleteness();

        //Function to print all values of an object in log as DEBUG
        abstract public function printAllValues();
    }
?>