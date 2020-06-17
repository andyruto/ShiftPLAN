<!--
-- file.php
--
-- File class representing a specific file on filesystem.
--
-- author: Andreas G.
-- last edit / by: 2020-06-14 / Andreas G.
-->
<?php
    final class File {
        //The path to the folder where the file is located in
        private $path = null;

        //The name of the file
        private $fileName = null;

        //The file extension of the file
        private $extension = null;

        //Constructor creating a file object
        public function __construct(string $path, string $fileName) {
            $this->path = $path;

            
        }
    }
?>