<?php
    /**
     * errorCode.php
     * 
     * PHP file containing all defined error codes.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
     */

    abstract class ErrorCode{
        const NoError = 0; //no errors occured
        const InvalidApiKey = 1; //api key was invalid
        const NoApiKeyGiven = 2; //the api key parameter was empty
        const MissingApiKeyPermission = 3; //Permission is missing for execution
        const InvalidSession = 4; //Session is invalid
        const NoSessionGiven = 5; //the session parameter was empty
        const SessionExpired = 6; //Session expiration date exited
        const UserNotFound = 7; //User not found in database
        const WrongPassword = 8; //Wrong password for user
        const ApiKeyNameAlreadyInUse = 9; //An api key with the given name is already in use
        const BadCyperText = 10; //Cyper text was bad
        const MalformedMessageOrInvalidMAC = 11; //message malformed
        const DatabaseValidationFailed = 12; // validation of the database failed
        const MigrationMissing = 17; //A required migration file is missing
    }
?>