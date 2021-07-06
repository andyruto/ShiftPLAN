<?php
    /**
     * errorCode.php
     * 
     * PHP file containing all defined error codes.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-08 / Maximilian T. | Kontr0x
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
        const EmptyParameter = 13; // parameter was empty
        const ValidationFailed = 14; // value mismatched regex
        const RequestBodyMalformed = 15; // request body couldnt be parsed
        const RequestBodyEmpty = 16; // request body was empty
        const MigrationMissing = 17; //A required migration file is missing
        const NoUserNameOrIdGiven = 18; //the user name or id parameter was empty
        const DefaultUserUsesDefaultPassword = 19; //default user still uses default passsword
        const DatabaseCorrupt = 20; //necessary database tables are corrupt, empty or not existing
        const ApiVersionNotDbVersion = 21; //api version is different form database version
        const UserIsHidden = 22; //The user is not visible
        const UserTypeNotMatching = 23; //The user isn't matching the required user type
        const ChallengeFailed = 24; //The saved challenge does not match the decryptet one
        const ChallengeExpired = 25; //Challenge expired
        const NotImplementedYet = 26; //Requested function is not implemented yet
        const ParameterMissmatch = 27; //Parameters do not match function requirements
        const UserAlreadyExists = 28; //When trying to create a user that already exists in db
        const TaskNotFound = 29; //Task not found in database
        const TaskAlreadyExists = 30; //Task name already in use
        const NoTaskIdGiven = 31; //Task name parameter was empty
        const MissingRights = 32; //User does have not enough rights to do this action
        const UnknownValue = 33; //Value of a filter is unknown
        const FilterNotMatching = 34; //With given filter no results were found in database
    }
?>