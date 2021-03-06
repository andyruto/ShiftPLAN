<?php
    /**
     * validation.php
     * 
     * PHP file containing all defined regex validations.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-18 / Maximilian T. | Kontr0x
     */

    abstract class Validation{
        const ApiKey = '/^[0-9a-zA-Z]{20}$/';
        const Session = '/^[0-9a-zA-Z]{20}$/';
        const UserName = '/^[0-9a-zA-Z]{3,20}$/';
        const Overtime = '/^(?:[0-9]|[1-9][0-9]|[1-9][0-9][0-9])$/';
        const WeeklyWorkingMinutes = '/^(?:[0-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-2][0-9][0-9][0-9]|[1-3][0]{3})$/';
        const WeeklyWorkingDays = '/^(?:[0-7]{1})$/';
        const YearVacationDays = '/^(?:[0-9]|[1-9][0-9]|[1][0-9][0-9]|[2][0-7][0-9]|[2][8][0])$/';
        const UserType = '/^(?:[0-2]{1})$/';
        const UserFilters = '/^(?:id|type|name|hidden|all){1}$/';
        const UserFiltersNoAdminRights = '/^(?:id|name){1}$/';
        const NameOfNumbersAndCharacters = '/^[0-9a-zA-Z]{1,20}$/';
        const AllUserReducedFilters = '/^(?:visible|invisible|all){1}$/';
        const AllUserFilters = '/^(?:complete){1}$/';
        const SimpleDateFormat = '/^(?:[1-9][0-9]{3})-(?:0[1-9]|1[0-2])-(?:0[1-9]|[1-2][0-9]|3[0-1])$/';
        const TimeSpanSearchFilters = '/^(?:id|appointedDay|start|end|requiredEmployees|lastModifiedBy|lastModified|taskId){1}$/';
        const ExtendedDateFormat = '/^(?:[1-9][0-9]{3})-(?:0[1-9]|1[0-2])-(?:0[1-9]|[1-2][0-9]|3[0-1]) (?:[0-1][0-9]|2[0-4]):(?:[0-5][0-9]):(?:[0-5][0-9])$/';
        const CommentOfNumbersAndCharacters64Len = '/(?:[\d\w ]{0,64})/';
        const ShiftSearchFilters = '/^(id|assignedUser|supervisorUser|task|shiftStart|shiftEnd|comment|lastModifiedBy|lastModified)$/';
    }
?>