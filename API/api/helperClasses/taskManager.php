<?php
    /**
     * taskManager.php
     * 
     * PHP file containing class to manage the tasks.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-06-23 / Maximilian T. | Kontr0x
     */

    class TaskManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $task = null; //Variable to store an task object
        
        private function __construct($taskId){
            Logger::getLogger()->log('DEBUG', 'Called task manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(isset($taskId)){
                //Looking for task in database
                $this->task = $this->eM->find('task', $taskId);

                if($this->task == null){
                    Logger::getLogger()->log('ERROR', "Task with id ".$taskId." doesn't exist in database");
                    $this->errorCode = ErrorCode::TaskNotFound;
                }else{
                    Logger::getLogger()->log('DEBUG', "Task with id ".$taskId." found in database");
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter task id is empty. Class probably created as TaskManager::creator');
                $this->errorCode = ErrorCode::NoTaskIdGiven;
            }
        }

        //Overload constructor to create new entities
        public static function creator(){
            return new self(null);
        }

        //Overload constructor to work with existing entities
        public static function obj($taskId){
            return new self($taskId);
        }

        //Function to create a task
        public function createTask($name){
            if($this->eM->getRepository('task')->findBy(array('name' => $name))[0] === Null){
                //Creating new task
                $newTask = new Task();
                $newTask->setName($name);
                //Storeing created task
                $this->eM->persist($newTask);
                //Flushing changes
                $this->eM->flush();
                return ErrorCode::NoError;
            }else{
                return ErrorCode::TaskAlreadyExists;
            }
        }

        //Function to remove the task from db
        public function removeTask(){
            if($this->errorCode == ErrorCode::NoError){
                $taskId = $this->task->getId();
                if($this->eM->getRepository('taskTimeSpan')->findBy(array('task_Id' => $taskId))[0] === Null){
                    if($this->eM->getRepository('shift')->findBy(array('task' => $taskId))[0] === Null){
                        //Removing task from database
                        $this->eM->remove($this->task);
                        //Flushing changes
                        $this->eM->flush();
                        Logger::getLogger()->log('DEBUG', 'Task with id '.$taskId.' got removed');

                    }else{
                        Logger::getLogger()->log('ERROR', 'Task with id '.$taskId.' didn\'t got remove! Task is connected to a shift');
                        $this->errorCode = ErrorCode::TaskIsConnectedToShift;
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'Task with id '.$taskId.' didn\'t got remove! Task is connected to a time span');
                    $this->errorCode = ErrorCode::TaskIsConnectedToTimeSpan;
                }
            }
            return $this->errorCode;
        }

        //Function to get all Tasks formatted and ready to print
        static public function getAllTasks(){
            $eM = Bootstrap::getEntityManager();
            $tasks = $eM->getrepository('task')->findAll();
            $tasksArray = array();
            foreach($tasks as $task){
                if(empty($eM->getRepository('taskTimeSpan')->findBy(array('task_id' => $task->getId())))){
                    $reccuring = false;
                }else{
                    $reccuring = true;
                }
                $taskArray = array('id' => $task->getId(), 'name' => $task->getName(), 'reccuring' => $reccuring);
                $tasksArray = array_merge($tasksArray, array($taskArray));
            }
            return array('tasks' => array_values($tasksArray));
        }

        //Function to get task formatted and ready to print
        public function getTask(){
            if($this->errorCode == ErrorCode::NoError){
                $task = array();
                if(empty($this->eM->getRepository('taskTimeSpan')->findBy(array('task_id' => $this->task->getId())))){
                    $reccuring = false;
                }else{
                    $reccuring = true;
                }
                $taskArray = array('id' => $this->task->getId(), 'name' => $this->task->getName(), 'reccuring' => $reccuring);
                $task = array_merge($task, array($taskArray));
                Logger::getLogger()->log('INFO', 'Returning task');
                return array('task' => array_values($task));
            }
        }

        //Returning the finish code
        public function getFinishCode(){
            return $this->errorCode;
        }

        //Returning database object of class
        public function getDbObject(){
            if($this->errorCode == ErrorCode::NoError){
                return $this->task;
            }
        }
    }
?>