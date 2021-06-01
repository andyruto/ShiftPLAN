<?php
    /**
     * validation.php
     * 
     * PHP file containing all defined regex validations.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-01 / Maximilian T. | Kontr0x
     */

    abstract class Validation{
        const ApiKey = '/^[0-9a-zA-Z]{20}$/';
        const Session = '/^[0-9a-zA-Z]{20}$/';
        const UserName = '/^[0-9a-zA-Z]{3,20}$/';
        const BooleanValue = '/^(?:True){1}$|^(?:False){1}$/';
        const Overtime = '/^(?:[0-9]|[1-9][0-9]|[1-9][0-9][0-9])$/';
        const WeeklyWorkingMinutes = '/^(?:[0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-2][0-9][0-9][0-9]|[1-3][0]{3})$/';
        const WeeklyWorkingDays = '/^(?:[0-6]{1})$/';
        const YearVacationDays = '/^(?:[0-9]|[1-9][0-9]|[1][0-9][0-9]|[2][0-7][0-9]|[2][8][0])$/';
        const UserType = '/^(?:[0-2]{1})$/';
    }
?>