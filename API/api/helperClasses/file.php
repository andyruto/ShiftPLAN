<!--
-- file.php
--
-- File class representing a specific file on filesystem.
--
-- author: Andreas G.
-- last edit / by: 2020-08-07 / Andreas G.
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

            $subStrings = explode('.', $fileName);
            $fileNameBuffer = '';
            $extensionBuffer = '';

            for ($i = 0; $i < sizeof($subStrings)-1; $i++) {
                $fileNameBuffer = $fileNameBuffer . $subStrings[$i];
                if ($i != sizeof($subStrings)-2) {
                    $fileNameBuffer = $fileNameBuffer . '.';
                }
            }

            $extensionBuffer = $subStrings[sizeof($subStrings)-1];

            $this->fileName = $fileNameBuffer;
            $this->extension = $extensionBuffer;
        }

        //Getter method for path property.
        //@return: The value of path property.
        public function getPath() : string {
            return $this->path;
        }

        //Getter method for fileName property.
        //@return: The value of fileName property.
        public function getFileName() : string {
            return $this->fileName;
        }

        //Getter method for extension property.
        //@return: The value of extension property.
        public function getExtension() : string {
            return $this->extension;
        }

        //Getter method for full path string.
        //@return: The full path string.
        public function getFullPath() : string {
            return $this->path . '' . $this->fileName . '.' . $this->extension;
        }
    }
?>