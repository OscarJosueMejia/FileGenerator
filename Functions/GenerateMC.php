<?php

function writeOnFile_MainController($tableNames, $tableInfo, $inputFields, $validationFields){

    $pluralName = $tableNames["pluralName"];
    $singularName = $tableNames["singularName"];

    $myfile = fopen("Generated_Scripts/"."$singularName".".php", "w") or die("Unable to open file!");
    
    $tablePK = "";
    foreach ($tableInfo as $key => $value) {
        if ($value["Key"] == 'PRI') {
            $tablePK = $value["Field"];
        } 
    }

    $importsAndHeads = 
"<?php 
    /**Created by FileGenerator 1.0*/
    /**CRUD CONTROLLER*/

    namespace Controllers\Mnt;

    use Controllers\PublicController;
    use Views\Renderer;
    use Utilities\Validators;
    use Dao\Mnt\\".$pluralName.";


    class ".$singularName." extends PublicController{\n";

    $arrayDefinitions=
    "
        private ".'$viewData'."= array();";


    $runFunction=
    "\n".' 
        public function run():void
        {
            $this->init();

            if (!$this->isPostBack()) {
                $this->procesarGet();
            }
            
            if ($this->isPostBack()) {
                $this->procesarPost();
            }

        $this->processView();
        Renderer::render("mnt/'.strtolower($singularName).'", $this->viewData);
    }';

    $varInit = "";
    $varCRUDSet = "";
    $arrSet = "";

    foreach ($tableInfo as $key => $value) {
        $varInit = $varInit ."\t\t". '$this->viewData["'.$value["Field"].'"] = "";'."\n\t";
        $varCRUDSet = $varCRUDSet . '$this->viewData["'.$value["Field"].'"],'."\n\t\t\t\t\t\t";

        if ($value["Null"] == 'NO' && $value["Key"] != 'PRI') {
            $varInit = $varInit ."\t\t". '$this->viewData["error_'.$value["Field"].'"] = array();'."\n\t";
        } 

        if (in_array($value["Field"], $inputFields)) {
            $varInit .= "\t\t". '$this->viewData["'.$value["Field"].'Arr"] = array();'."\n\t";
            $arrSet .= '
            $this->arr_'.$value["Field"].' = array(
                array("value" => "VAL1", "text" => "Text1"),
                array("value" => "VAL2", "text" => "Text2"),
                array("value" => "VAL3", "text" => "Text3"),
            );
            $$this->viewData["'.$value["Field"].'Arr"] = $this->arr_'.$value["Field"].';
            '."\n";
        }
    }

    $initFunction =
    '
        private function init(){
            $this->viewData = array();
            $this->viewData["mode"] = "";
            $this->viewData["mode_desc"] = "";
            $this->viewData["crsf_token"] = "";

            //Var set guided by Table Info
    '
    .$varInit.
    '       
            // ------
            
            $this->viewData["btnEnviarText"] = "Guardar";
            $this->viewData["readonly"] = false;
            $this->viewData["showBtn"] = true;

            $this->arrModeDesc = array(
                "INS"=>"Nuevo '.$singularName.'",
                "UPD"=>"Editando %s %s",
                "DSP"=>"Detalle de %s %s",
                "DEL"=>"Eliminando %s %s"
            );

            // Array Options, Change this asap.
            '.$arrSet.'

        }';


    $procesarGetFunction = 
    '
    
        private function procesarGet(){
            if (isset($_GET["mode"])) {
                $this->viewData["mode"] = $_GET["mode"];
                if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                    error_log("Error: El modo solicitado no existe.");
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=mnt_'.strtolower($pluralName).'",
                        "No se puede procesar su solicitud!"
                    );
                }
            }
            if ($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
                $this->viewData["'.$tablePK.'"] = intval($_GET["id"]);
                $tmpArray = '.$pluralName.'::getById($this->viewData["'.$tablePK.'"]);
                \Utilities\ArrUtils::mergeFullArrayTo($tmpArray, $this->viewData);
            }
        }';

    
    $validations = "";
    foreach ($tableInfo as $key => $value) {

        if ($value["Null"] == 'NO' && $value["Key"] != 'PRI') {
            $validations = $validations ."\n".
            '
            if (Validators::IsEmpty($this->viewData["'.$value["Field"].'"])) {
                $this->viewData["error_'.$value["Field"].'"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
            ';
        }else{
            if ($validationFields != "") {
                if ((in_array($value["Field"], $validationFields)) && $value["Null"] != 'NO') {
                    $validations = $validations ."\n".
                    '
            if (Validators::IsEmpty($this->viewData["'.$value["Field"].'"])) {
                $this->viewData["error_'.$value["Field"].'"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
                    ';   
                }
            }
        }
    }

    $procesarPostFunction =
    '
        private function procesarPost()
        {
            $hasErrors = false;
            \Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);
            if (isset($_SESSION[$this->name . "crsf_token"])
                && $_SESSION[$this->name . "crsf_token"] !== $this->viewData["crsf_token"]
            ) {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=mnt_'.strtolower($pluralName).'",
                    "ERROR: Algo inesperado sucedió con la petición. Intente de nuevo."
                );
            }

            //Validation Zone
        '.$validations.'
            //------
            
            if (!$hasErrors) {
                $result = null;

                switch($this->viewData["mode"]) {
                case "INS":
                    $result = '.$pluralName.'::insert(
                        '.$varCRUDSet.'
                    );

                    if ($result) {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=mnt_'.strtolower($pluralName).'",
                                "Registro Guardado Satisfactoriamente!"
                            );
                    }
                    break;

                case "UPD":
                    $result = '.$pluralName.'::update(
                        '.$varCRUDSet.'
                        intval($this->viewData["'.$tablePK.'"])
                    );

                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=mnt_'.strtolower($pluralName).'",
                            "Registro Actualizado Satisfactoriamente!"
                        );
                    }
                    break;

                case "DEL":
                    $result = '.$pluralName.'::delete(
                        intval($this->viewData["'.$tablePK.'"])
                    );

                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=mnt_'.strtolower($pluralName).'",
                            "Registro Eliminado Satisfactoriamente!"
                        );
                    }
                    break;
                }
            }
        }';


    $processViewFunction=
    '
        private function processView(){
            
            if ($this->viewData["mode"] === "INS") {
                $this->viewData["mode_desc"]  = $this->arrModeDesc["INS"];
                $this->viewData["btnEnviarText"] = "Guardar Nuevo";
            } else {
                $this->viewData["mode_desc"]  = sprintf(
                    $this->arrModeDesc[$this->viewData["mode"]],
                    $this->viewData["'.$tablePK.'"],
                    "Cambie este valor por uno más descriptivo-Linea:171"
                    // $this->viewData["descriptive_value_here"]
                );

                /*
                //Personalice los arreglos para los datos que van en un select o radiobutton
                $this->viewData["enlace_arreglo"]
                    = \Utilities\ArrUtils::objectArrToOptionsArray(
                        $this->arreglo_definido_anteriormente,
                        "value",
                        "text",
                        "value",
                        $this->viewData["variable_plantilla"]
                );
                */

                if ($this->viewData["mode"] === "DSP") {
                    $this->viewData["readonly"] = true;
                    $this->viewData["showBtn"] = false;
                }
                if ($this->viewData["mode"] === "DEL") {
                    $this->viewData["readonly"] = true;
                    $this->viewData["btnEnviarText"] = "Eliminar";
                }
                if ($this->viewData["mode"] === "UPD") {
                    $this->viewData["btnEnviarText"] = "Actualizar";
                }
            }
            $this->viewData["crsf_token"] = md5(getdate()[0] . $this->name);
            $_SESSION[$this->name . "crsf_token"] = $this->viewData["crsf_token"];
        }
    }?>';


    

    fwrite($myfile, 
        $importsAndHeads. 
        $arrayDefinitions. 
        $runFunction.
        $initFunction.
        $procesarGetFunction.
        $procesarPostFunction.
        $processViewFunction);
    fclose($myfile);
}

?>