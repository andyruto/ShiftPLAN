<?php
    /**
     * validation.php
     * 
     * PHP file containing all defined regex validations.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-26 / Maximilian T. | Kontr0x
     */

    abstract class Validation{
        const ApiKey = '/^[0-9a-zA-Z]{20}$/';
        const Session = '/^[0-9a-zA-Z]{20}$/';
        const UserName = '/^[0-9a-zA-Z]{3,20}$/';
    }
?>