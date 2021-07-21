<?php
    /**
     * shiftManager.php
     * 
     * PHP file containing class to manage the shifts.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-18 / Maximilian T. | Kontr0x
     */

    class ShiftManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $shift = null; //Variable to store an shift object
        
        private function __construct($shiftId){
            Logger::getLogger()->log('INFO', 'Called shift manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(isset($shiftId)){
                //Looking for shift in database
                $this->shift = $this->eM->find('shift', $shiftId);

                if($this->shift == null){
                    Logger::getLogger()->log('ERROR', "Shift with id ".$shiftId." doesn't exist in database");
                    $this->errorCode = ErrorCode::ShiftNotFound;
                }else{
                    Logger::getLogger()->log('DEBUG', "Shift with id ".$shiftId." found in database");
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter shift id is empty. Class probably created as ShiftManager::handler');
            }
        }

        //Overload constructor to create, search entities
        public static function handler(){
            Logger::getLogger()->log('INFO', 'Class created as ShiftManager::handler');
            return new self(null);
        }

        //Overload constructor to work with existing entities
        public static function obj($shiftId){
            if(is_integer($shiftId)){
                return new self($shiftId);
            }else{
                $tmpObj = new self(null);
                $tmpObj->errorCode = ErrorCode::ValidationFailed;
                Logger::getLogger()->log('ERROR', 'id failed regex validation');    
                return $tmpObj;
            }
        }

        //Function to create a shift
        public function createShift($assigned_user, $supervisor_user, $connected_task_id, $shift_start, $shift_end, $comment, $last_modified_by){
            Logger::getLogger()->log('INFO', 'Creating new shift');
            //Creating new shift
            $newShift = new Shift();
            if(is_integer($assigned_user)){
                if($this->userExists($assigned_user)){
                    $newShift->setAssigned_user($assigned_user);
                }
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'assigned_user\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(is_integer($supervisor_user)){
                if($this->userExists($supervisor_user)){
                    $newShift->setSupervisor_user($supervisor_user);
                }
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'supervisor_user\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(is_integer($connected_task_id)){
                if($this->taskExists($connected_task_id)){
                    $newShift->setTask($connected_task_id);
                }
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'connected_task_id\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(preg_match(Validation::ExtendedDateFormat, $shift_start)){
                $newShift->setShift_start($shift_start);
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'shift_start\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(preg_match(Validation::ExtendedDateFormat, $shift_end)){
                $newShift->setShift_end($shift_end);
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'shift_end\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(preg_match(Validation::CommentOfNumbersAndCharacters64Len, $comment)){
                $newShift->setComment($comment);
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'comment\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(is_integer($last_modified_by)){
                $newShift->setLast_modified_by($last_modified_by);
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'last_modified_by\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            $newShift->setLast_modified();
            if(self::$errorCode == ErrorCode::NoError){
                //Storeing created shift
                $this->eM->persist($newShift);
                //Flushing changes
                $this->eM->flush();
            }
            return $this->errorCode;
        }

        //Function to modify the shift from db
        public function modifyShift($arrayOfChanges){
            if($this->errorCode == ErrorCode::NoError){
                if(array_key_exists("lastModifiedBy", $arrayOfChanges)){
                    $this->shift->setLast_modified();
                    if(is_integer($arrayOfChanges["lastModifiedBy"])){
                        $this->shift->setLast_modified_by($arrayOfChanges["lastModifiedBy"]);
                    }else{
                        Logger::getLogger()->log('ERROR', 'validation failed on \'lastModifiedBy\'');
                        $this->errorCode = ErrorCode::ValidationFailed;
                    }
                    if(array_key_exists("assignedUser", $arrayOfChanges)){
                        if(is_integer($arrayOfChanges["assignedUser"])){
                            if($this->userExists($arrayOfChanges["assignedUser"])){
                                $this->shift->setAssigned_user($arrayOfChanges["assignedUser"]);
                                Logger::getLogger()->log('DEBUG', 'Shift modified assigned user to '.$arrayOfChanges["assignedUser"]);
                            }
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'assignedUser\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("supervisorUser", $arrayOfChanges)){
                        if(is_integer($arrayOfChanges["supervisorUser"])){
                            if($this->userExists($arrayOfChanges["supervisorUser"])){
                                $this->shift->setSupervisor_user($arrayOfChanges["supervisorUser"]);
                                Logger::getLogger()->log('DEBUG', 'Shift modified supervisor user to '.$arrayOfChanges["supervisorUser"]);
                            }
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'supervisorUser\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("task", $arrayOfChanges)){
                        if(is_integer($arrayOfChanges["task"])){
                            if($this->taskExists($arrayOfChanges["task"])){
                                $this->shift->setTask($arrayOfChanges["task"]);
                                Logger::getLogger()->log('DEBUG', 'Shift modified task to '.$arrayOfChanges["task"]);
                            }
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'task\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("shiftStart", $arrayOfChanges)){
                        if(preg_match(Validation::ExtendedDateFormat, $arrayOfChanges["shiftStart"])){
                            $this->shift->setShift_start($arrayOfChanges["shiftStart"]);
                            Logger::getLogger()->log('DEBUG', 'Shift modified shift start to '.$arrayOfChanges["shiftStart"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'shiftStart\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("shiftEnd", $arrayOfChanges)){
                        if(preg_match(Validation::ExtendedDateFormat, $arrayOfChanges["shiftEnd"])){
                            $this->shift->setShift_end($arrayOfChanges["shiftEnd"]);
                            Logger::getLogger()->log('DEBUG', 'Shift modified shift end id to '.$arrayOfChanges["shiftEnd"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'shiftEnd\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("comment", $arrayOfChanges)){
                        if(preg_match(Validation::CommentOfNumbersAndCharacters64Len, $arrayOfChanges["comment"])){
                            $this->shift->setComment($arrayOfChanges["comment"]);
                            Logger::getLogger()->log('DEBUG', 'Shift modified comment id to '.$arrayOfChanges["comment"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'comment\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(self::$errorCode == ErrorCode::NoError){
                        //Flushing changes
                        $this->eM->flush();
                        Logger::getLogger()->log('INFO', 'Shift with id '.$this->shift->getId().' got modified');
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'Modify shift function canceled due to missing last_modified_by parameter');
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Modify shift function canceled due to '.$this->errorCode.' error already occured');
            }
            return $this->errorCode;
        }

        //Function to search for a shift
        public function searchShifts($filter, $value){
            $shifts = array();
            $shiftArray = array();
            if($this->errorCode == ErrorCode::NoError){
                if(preg_match(Validation::ShiftSearchFilters, $filter)){
                    $shiftDbObjects = $this->eM->getRepository('shift')->findBy(array($filter => $value));
                    if($shiftDbObjects !== Null){
                        //Formatting the output of the found shifts
                        foreach($shiftDbObjects as $shift){
                            $shiftArray = array('id' => $shift->getId(), 'assignedUser' => $shift->getAssigned_user(), 'supervisorUser' => $shift->getSupervisor_user(), 'task' => $shift->getTask(), 'shiftStart' => $shift->getShift_start(), 'shiftEnd' => $shift->getShift_end(), 'comment' => $shift->getComment(), 'lastModifiedBy' => $shift->getLast_modified_by(), 'lastModified' => $shift->getLast_modified());
                            $shifts = array_merge($shifts, array($shiftArray));
                        }
                    }else{
                        Logger::getLogger()->log('ERROR', 'Filter \''.$filter.'\' = \''.$value.'\' didnt match');
                        $this->errorCode = ErrorCode::ShiftNotFound;
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'filter\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
            }else{
                Logger::getLogger()->log('ERROR', 'search shifts function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('shifts' => array_values($shifts));
        }

        //Function to get the shift
        public function getShift(){
            $shift = array();
            $shiftArray = array();
            if($this->errorCode == ErrorCode::NoError){
                $shiftArray = array('id' => $this->shift->getId(), 'assignedUser' => $this->shift->getAssigned_user(), 'supervisorUser' => $this->shift->getSupervisor_user(), 'task' => $this->shift->getTask(), 'shiftStart' => $this->shift->getShift_start(), 'shiftEnd' => $this->shift->getShift_end(), 'comment' => $this->shift->getComment(), 'lastModifiedBy' => $this->shift->getLast_modified_by(), 'lastModified' => $this->shift->getLast_modified());
                $shift = array_merge($shift, array($shiftArray));
                Logger::getLogger()->log('INFO', 'Returning shift stored from shift manager');
            }else{
                Logger::getLogger()->log('ERROR', 'get shift function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('shift' => array_values($shift));
        }

        //Function to get all shifts
        public function getAllShifts(){
            $shifts = array();
            $shiftArray = array();
            if($this->errorCode == ErrorCode::NoError){
                $shiftDbObjects = $this->eM->getRepository('shift')->findAll();
                if($shiftDbObjects !== Null){
                    //Formatting the output of the found shifts
                    foreach($shiftDbObjects as $shift){
                        $shiftArray = array('id' => $shift->getId(), 'assignedUser' => $shift->getAssigned_user(), 'supervisorUser' => $shift->getSupervisor_user(), 'task' => $shift->getTask(), 'shiftStart' => $shift->getShift_start(), 'shiftEnd' => $shift->getShift_end(), 'comment' => $shift->getComment(), 'lastModifiedBy' => $shift->getLast_modified_by(), 'lastModified' => $shift->getLast_modified());
                        $shifts = array_merge($shifts, array($shiftArray));
                    }
                }
            }else{
                Logger::getLogger()->log('ERROR', 'search all shifts function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('shifts' => array_values($shifts));
        }

        //Function to remove the shift from db
        public function removeShift(){
            if($this->errorCode == ErrorCode::NoError){
                $shiftId = $this->shift->getId();
                //Removing shift from database
                $this->eM->remove($this->shift);
                //Flushing changes
                $this->eM->flush();
                Logger::getLogger()->log('INFO', 'Shift with id '.$shiftId.' got removed');
            }else{
                Logger::getLogger()->log('ERROR', 'Remove shift function canceled due to '.$this->errorCode.' error already occured');
            }
            return $this->errorCode;
        }

        //Function to check if user with id exists
        public function userExists($userId){
            if($this->errorCode == ErrorCode::NoError){
                if($this->eM->find('user', $userId) !== Null){
                    return True;
                }
                Logger::getLogger()->log('ERROR', 'User with id '.$userId.' not found in database');
                $this->errorCode = ErrorCode::UserNotFound;
            }else{
                Logger::getLogger()->log('ERROR', 'Check if user exists function canceled due to '.$this->errorCode.' error already occured');
            }
            return False;
        }

        //Function to check if task with id exists
        public function taskExists($taskId){
            if($this->errorCode == ErrorCode::NoError){
                if($this->eM->find('task', $taskId) !== Null){
                    return True;
                }
                Logger::getLogger()->log('ERROR', 'Task with id '.$taskId.' not found in database');
                $this->errorCode = ErrorCode::TaskNotFound;
            }else{
                Logger::getLogger()->log('ERROR', 'Check if task exists function canceled due to '.$this->errorCode.' error already occured');
            }
            return False;
        }

        //Returning the finish code
        public function getFinishCode(){
            return $this->errorCode;
        }

        //Returning database object of class
        public function getDbObject(){
            if($this->errorCode == ErrorCode::NoError){
                return $this->shift;
            }else{
                Logger::getLogger()->log('ERROR', 'Get database function canceled due to '.$this->errorCode.' error already occured');
            }
        }
    }
?>