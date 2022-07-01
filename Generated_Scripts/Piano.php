<?php 
    /**Created by FileGenerator 1.0*/
    /**CRUD CONTROLLER*/

    namespace Controllers\Mnt;

    use Controllers\PublicController;
    use Views\Renderer;
    use Utilities\Validators;
    use Dao\Mnt\Pianos;


    class Piano extends PublicController{

        private $viewData= array();
 
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
        Renderer::render("mnt/piano", $this->viewData);
    }
        private function init(){
            $this->viewData = array();
            $this->viewData["mode"] = "";
            $this->viewData["mode_desc"] = "";
            $this->viewData["crsf_token"] = "";

            //Var set guided by Table Info
    		$this->viewData["pianoid"] = "";
			$this->viewData["pianodsc"] = "";
			$this->viewData["error_pianodsc"] = array();
			$this->viewData["pianobio"] = "";
			$this->viewData["error_pianobio"] = array();
			$this->viewData["pianosales"] = "";
			$this->viewData["error_pianosales"] = array();
			$this->viewData["pianoimguri"] = "";
			$this->viewData["pianoimgthb"] = "";
			$this->viewData["pianoprice"] = "";
			$this->viewData["error_pianoprice"] = array();
			$this->viewData["pianoest"] = "";
			$this->viewData["pianoestArr"] = array();
	       
            // ------
            
            $this->viewData["btnEnviarText"] = "Guardar";
            $this->viewData["readonly"] = false;
            $this->viewData["showBtn"] = true;

            $this->arrModeDesc = array(
                "INS"=>"Nuevo Piano",
                "UPD"=>"Editando %s %s",
                "DSP"=>"Detalle de %s %s",
                "DEL"=>"Eliminando %s %s"
            );

            // Array Options, Change this asap.
            
            $this->arr_pianoest = array(
                array("value" => "VAL1", "text" => "Text1"),
                array("value" => "VAL2", "text" => "Text2"),
                array("value" => "VAL3", "text" => "Text3"),
            );
            $$this->viewData["pianoestArr"] = $this->arr_pianoest;
            


        }
    
        private function procesarGet(){
            if (isset($_GET["mode"])) {
                $this->viewData["mode"] = $_GET["mode"];
                if (!isset($this->arrModeDesc[$this->viewData["mode"]])) {
                    error_log("Error: El modo solicitado no existe.");
                    \Utilities\Site::redirectToWithMsg(
                        "index.php?page=mnt_pianos",
                        "No se puede procesar su solicitud!"
                    );
                }
            }
            if ($this->viewData["mode"] !== "INS" && isset($_GET["id"])) {
                $this->viewData["pianoid"] = intval($_GET["id"]);
                $tmpArray = Pianos::getById($this->viewData["pianoid"]);
                \Utilities\ArrUtils::mergeFullArrayTo($tmpArray, $this->viewData);
            }
        }
        private function procesarPost()
        {
            $hasErrors = false;
            \Utilities\ArrUtils::mergeArrayTo($_POST, $this->viewData);
            if (isset($_SESSION[$this->name . "crsf_token"])
                && $_SESSION[$this->name . "crsf_token"] !== $this->viewData["crsf_token"]
            ) {
                \Utilities\Site::redirectToWithMsg(
                    "index.php?page=mnt_pianos",
                    "ERROR: Algo inesperado sucedió con la petición. Intente de nuevo."
                );
            }

            //Validation Zone
        

            if (Validators::IsEmpty($this->viewData["pianodsc"])) {
                $this->viewData["error_pianodsc"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
                    

            if (Validators::IsEmpty($this->viewData["pianobio"])) {
                $this->viewData["error_pianobio"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
                    

            if (Validators::IsEmpty($this->viewData["pianosales"])) {
                $this->viewData["error_pianosales"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
                    

            if (Validators::IsEmpty($this->viewData["pianoprice"])) {
                $this->viewData["error_pianoprice"][]
                    = "Este campo es requerido.";
                $hasErrors = true;
            }  
                    
            //------
            
            if (!$hasErrors) {
                $result = null;

                switch($this->viewData["mode"]) {
                case "INS":
                    $result = Pianos::insert(
                        $this->viewData["pianoid"],
						$this->viewData["pianodsc"],
						$this->viewData["pianobio"],
						$this->viewData["pianosales"],
						$this->viewData["pianoimguri"],
						$this->viewData["pianoimgthb"],
						$this->viewData["pianoprice"],
						$this->viewData["pianoest"],
						
                    );

                    if ($result) {
                            \Utilities\Site::redirectToWithMsg(
                                "index.php?page=mnt_pianos",
                                "Registro Guardado Satisfactoriamente!"
                            );
                    }
                    break;

                case "UPD":
                    $result = Pianos::update(
                        $this->viewData["pianoid"],
						$this->viewData["pianodsc"],
						$this->viewData["pianobio"],
						$this->viewData["pianosales"],
						$this->viewData["pianoimguri"],
						$this->viewData["pianoimgthb"],
						$this->viewData["pianoprice"],
						$this->viewData["pianoest"],
						
                        intval($this->viewData["pianoid"])
                    );

                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=mnt_pianos",
                            "Registro Actualizado Satisfactoriamente!"
                        );
                    }
                    break;

                case "DEL":
                    $result = Pianos::delete(
                        intval($this->viewData["pianoid"])
                    );

                    if ($result) {
                        \Utilities\Site::redirectToWithMsg(
                            "index.php?page=mnt_pianos",
                            "Registro Eliminado Satisfactoriamente!"
                        );
                    }
                    break;
                }
            }
        }
        private function processView(){
            
            if ($this->viewData["mode"] === "INS") {
                $this->viewData["mode_desc"]  = $this->arrModeDesc["INS"];
                $this->viewData["btnEnviarText"] = "Guardar Nuevo";
            } else {
                $this->viewData["mode_desc"]  = sprintf(
                    $this->arrModeDesc[$this->viewData["mode"]],
                    $this->viewData["pianoid"],
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
    }?>