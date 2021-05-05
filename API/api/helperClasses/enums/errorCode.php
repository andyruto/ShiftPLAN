<?php
    /**
     * errorCode.php
     * 
     * PHP file containing all defined error codes.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    abstract class ErrorCode{
        const NoError = 0; //no errors occured
        const InvalidApiKey = 1; //api key was invalid
        const NoApiKeyGiven = 2; //the api key parameter was empty
    }
?>