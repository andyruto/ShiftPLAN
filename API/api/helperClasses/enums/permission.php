<?php
    /**
     * permission.php
     * 
     * PHP file containing all defined api key permissions.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    abstract class Permission{
        const ApiKeyRead = "getApiKey_read";
        const ApiKeyWrite = "getApiKey_write";
        const SessionsRead = "getSessions_read";
        const SessionsWrite = "getSessions_write";
        const UserRead = "getUsers_read";
        const UserWrite = "getUsers_write";
        const TasksRead = "getTasks_read";
        const TasksWrite = "getTasks_write";
        const VacationRead = "getVacation_read";
        const VacationWrite = "getVacation_write";
        const SickNoteRead = "getSick_note_read";
        const SickNoteWrite = "setSick_note_write";
        const ShiftRead = "getShift_read";
        const ShiftWrite = "getShift_write";
    }
?>