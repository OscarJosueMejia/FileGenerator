<?php
/**
 * PHP Version 7
 * DB Configuration Script - File Generator
 *
 * @category General 
 * @author   Oscar Josue Mejia Seren 
 * @version 1.0
 */

    define("HOST", "mysql:host=localhost; dbname=nw202202");
    define("USER", "");
    define("PASS", "");

    class GetTableDesc {
        private $tableData;
        private $databaseLink;
    
        public function __construct() {
            try {
                $this->tableData = array();
                $this->databaseLink = new PDO(HOST, USER, PASS);
            } catch (\Throwable $th) {
                echo "<h3 style='color:red;'>Verifique los parámetros HOST, USER, PASS de config.php</h3>";
                die;
            }
        }
    
        private function setNames() {
            return $this->databaseLink->query("SET NAMES 'utf8'");
        }
    
        public function getData($tableName) {
            self::setNames();

            try {
            $sql = "DESCRIBE ".$tableName.";";

            foreach ($this->databaseLink->query($sql) as $res) {
                $this->tableData[] = $res;
            }

            return $this->tableData;

            } catch (\Throwable $th) {
                // echo "<h3 style='color:red'}>El nombre de tabla proporcionada '". $tableName . "' generó una excepción: </h3>\n" . $th;
                return false;
                die;
            }

            $this->databaseLink = null; /* Close Connection */
        }
    }
?>