<?php

function writeOnFile_ListController($tableNames, $tableInfo){

    $pluralName = $tableNames["pluralName"];
    $DaoName = "Dao".$pluralName;

    $myfile = fopen("Generated_Scripts/"."$pluralName".".php", "w") or die("Unable to open file!");
    
    $ScriptContent = 
'<?php 
    /**Created by FileGenerator 1.0*/
    /**LIST CONTROLLER*/

    namespace Controllers\Mnt;

    use Controllers\PublicController;
    use Dao\Mnt\\'.$pluralName.' as '.$DaoName.';
    use Views\Renderer;
    
    class '.$pluralName.' extends PublicController
    {
        public function run():void
        {
            $viewData = array();
            $viewData["'.$pluralName.'"] = '.$DaoName.'::getAll();
          
            Renderer::render("mnt/'.strtolower($pluralName).'", $viewData);
        }
    }
    
    ?>';



    fwrite($myfile,$ScriptContent);
    fclose($myfile);
}
?>