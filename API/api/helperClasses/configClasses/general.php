<!--
-- general.php
--
-- PHP file containing functions to fill an validate configs.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-10 / Maximilian T. | Kontr0x
-->
<?php
    abstract class General{

        //Function to fill the paramters with values
        //@param $key: Key name
        //@param $value: Value of paramter
        public function putInValue(string $key, $value){
            
            if(property_exists($this, $key)):
                //static::$key=$value;
                $class = new ReflectionClass(get_class($this));
                $property = $class->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($this, $value);
                $property->setAccessible(false);
                $property = null;
                $class = null;
            endif;
        }

        //Function to validate the properties values
        abstract public function checkCompleteness();

    }
?>