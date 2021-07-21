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
        const ApiKeyRead = "ApiKey_read";
        const ApiKeyWrite = "ApiKey_write";
        const SessionsRead = "Sessions_read";
        const SessionsWrite = "Sessions_write";
        const UserRead = "Users_read";
        const UserWrite = "Users_write";
        const TasksRead = "Tasks_read";
        const TasksWrite = "Tasks_write";
        const VacationRead = "Vacation_read";
        const VacationWrite = "Vacation_write";
        const SickNoteRead = "Sick_note_read";
        const SickNoteWrite = "Sick_note_write";
        const ShiftRead = "Shift_read";
        const ShiftWrite = "Shift_write";
    }
?>