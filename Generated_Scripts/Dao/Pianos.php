<?php 
    /**Created by FileGenerator 1.0
     * DAO file
    */

    namespace Dao\Mnt;
    use Dao\Table;
    
    class Pianos extends Table {

        public static function getAll()
        {
            $sqlstr = "select * from pianos;";  
            return self::obtenerRegistros($sqlstr, array());
        }
    

        public static function getById(int $pianoid)
        {
            $sqlstr = "SELECT * from `pianos` where pianoid=:pianoid;";
            $sqlParams = array("pianoid" => $pianoid);
            
            return self::obtenerUnRegistro($sqlstr, $sqlParams);
        }
    

        public static function insert(
            $pianodsc,
			$pianobio,
			$pianosales,
			$pianoimguri,
			$pianoimgthb,
			$pianoprice,
			$pianoest,
			
        ){
            $sqlstr = "INSERT INTO `pianos` (
                `pianodsc`,
			`pianobio`,
			`pianosales`,
			`pianoimguri`,
			`pianoimgthb`,
			`pianoprice`,
			`pianoest`)
            VALUES(
                :pianodsc,
			:pianobio,
			:pianosales,
			:pianoimguri,
			:pianoimgthb,
			:pianoprice,
			:pianoest);";
            
            $sqlParams =[
                "pianodsc" => $pianodsc,
			"pianobio" => $pianobio,
			"pianosales" => $pianosales,
			"pianoimguri" => $pianoimguri,
			"pianoimgthb" => $pianoimgthb,
			"pianoprice" => $pianoprice,
			"pianoest" => $pianoest,
			
            ];

            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    

        public static function update(
            $pianodsc,
			$pianobio,
			$pianosales,
			$pianoimguri,
			$pianoimgthb,
			$pianoprice,
			$pianoest,
			
            $pianoid 
        ){

            $sqlstr = "UPDATE `pianos` SET

            `pianodsc` = :pianodsc, `pianobio` = :pianobio, `pianosales` = :pianosales, `pianoimguri` = :pianoimguri, `pianoimgthb` = :pianoimgthb, `pianoprice` = :pianoprice, `pianoest` = :pianoest

            where `pianoid`=:pianoid;";


            $sqlParams =[
                "pianodsc" => $pianodsc,
			"pianobio" => $pianobio,
			"pianosales" => $pianosales,
			"pianoimguri" => $pianoimguri,
			"pianoimgthb" => $pianoimgthb,
			"pianoprice" => $pianoprice,
			"pianoest" => $pianoest,
			
                "pianoid" => $pianoid
            ];

            return self::executeNonQuery($sqlstr, $sqlParams);
        }
    

        public static function delete($pianoid){
            $sqlstr = "DELETE from `pianos` where pianoid=:pianoid;";

            $sqlParams = [
            "pianoid" => $pianoid
            ];

            return self::executeNonQuery($sqlstr, $sqlParams);
            }
        }?>