<?php

function writeOnFile_DAO($tableNames, $tableInfo){

    $pluralName = $tableNames["pluralName"];
    $tableName = $tableNames["tableName"];

    $myfile = fopen("Generated_Scripts/Dao/"."$pluralName".".php", "w") or die("Unable to open file!");
    
    $tablePK = "";
    $paramsListInsert = "";
    $paramsListUpdate = "";
    $queryFirstParams = "";
    $querySecondParams = "";
    $associativeParams = "";
    $sqlParamsInsert = "";
    $sqlParamsUpdate = "";

    foreach ($tableInfo as $key => $value) {

        if (($value["Key"] != 'PRI')) {
            $paramsListInsert .= '$'.$value["Field"].",\n\t\t\t";
            $paramsListUpdate .= '$'.$value["Field"].",\n\t\t\t";
            $queryFirstParams .= '`'.$value["Field"]."`,\n\t\t\t";
            $querySecondParams .= ':'.$value["Field"].",\n\t\t\t";
            $sqlParamsInsert .= '"'.$value["Field"].'"'.' => '.'$'.$value["Field"].",\n\t\t\t";
            $sqlParamsUpdate .= '"'.$value["Field"].'"'.' => '.'$'.$value["Field"].",\n\t\t\t";
            $associativeParams .= '`'.$value["Field"].'` = :'.$value["Field"].', ';
        }
        else if ($value["Key"] == 'PRI' && $value["Extra"] != 'auto_increment') {
            $paramsListInsert .= '$'.$value["Field"].",\n\t\t\t";
            $queryFirstParams .= '`'.$value["Field"]."`,\n\t\t\t";
            $querySecondParams .= ':'.$value["Field"].",\n\t\t\t";
            $sqlParamsInsert .= '"'.$value["Field"].'"'.' => '.'$'.$value["Field"].",\n\t\t\t";
       }

        if ($value["Key"] == 'PRI') {
            $tablePK = $value["Field"];
        } 
    }
    	
    $queryFirstParams = trim($queryFirstParams);
    $querySecondParams = trim($querySecondParams);
    $associativeParams = trim($associativeParams);
    
    $queryFirstParams = substr_replace($queryFirstParams ,"", -1);
    $querySecondParams = substr_replace($querySecondParams ,"", -1);
    $associativeParams = substr_replace($associativeParams ,"", -1);

    $importsAndHeads = 
"<?php 
/**Created by FileGenerator 1.0
 * DAO file
*/

namespace Dao\Mnt;
use Dao\Table;

class ".$pluralName." extends Table {\n";

$getAllFunction =
'
    public static function getAll()
    {
        $sqlstr = "select * from `'.strtolower($tableName).'`;";  
        return self::obtenerRegistros($sqlstr, array());
    }
';


$getByIdFunction =
'

    public static function getById($'.$tablePK.')
    {
        $sqlstr = "SELECT * from `'.strtolower($tableName).'` where '.$tablePK.'=:'.$tablePK.';";
        $sqlParams = array("'.$tablePK.'" => $'.$tablePK.');
        
        return self::obtenerUnRegistro($sqlstr, $sqlParams);
    }
';

$insertFunction =
"\n".'
    public static function insert(
        '.$paramsListInsert.'
    ){
        $sqlstr = "INSERT INTO `'.strtolower($tableName).'` (
            '.$queryFirstParams.')
        VALUES(
            '.$querySecondParams.');";
        
        $sqlParams =[
            '.$sqlParamsInsert.'
        ];

        return self::executeNonQuery($sqlstr, $sqlParams);
    }
';

$updateFunction =
"\n".'
    public static function update(
        '.$paramsListUpdate.'
        $'.$tablePK.' 
    ){

        $sqlstr = "UPDATE `'.strtolower($tableName).'` SET

        '.$associativeParams.'

        where `'.$tablePK.'`=:'.$tablePK.';";


        $sqlParams =[
            '.$sqlParamsUpdate.'
            '.'"'.$tablePK.'" => $'.$tablePK.'
        ];

        return self::executeNonQuery($sqlstr, $sqlParams);
    }
';

$deleteFunction =
"\n".'
    public static function delete($'.$tablePK.'){
        $sqlstr = "DELETE from `'.strtolower($tableName).'` where '.$tablePK.'=:'.$tablePK.';";

        $sqlParams = [
        '.'"'.$tablePK.'" => $'.$tablePK.'
        ];
        
        try {
        return self::executeNonQuery($sqlstr, $sqlParams);
        } catch (\Throwable $th) {
            echo "<h2>ERROR: No se puede eliminar este registro.</h2>"."\n"."$th";
            die;
        }
    }
}?>';


    fwrite($myfile,
        $importsAndHeads.
        $getAllFunction.
        $getByIdFunction.
        $insertFunction.
        $updateFunction.
        $deleteFunction
    );
    fclose($myfile);
}
?>