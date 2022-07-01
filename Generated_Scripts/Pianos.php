<?php 
    /**Created by FileGenerator 1.0*/
    /**LIST CONTROLLER*/

    namespace Controllers\Mnt;

    use Controllers\PublicController;
    use Dao\Mnt\Pianos as DaoPianos;
    use Views\Renderer;
    
    class Pianos extends PublicController
    {
        public function run():void
        {
            $viewData = array();
            $viewData["Pianos"] = DaoPianos::getAll();
          
            Renderer::render("mnt/pianos", $viewData);
        }
    }
    
    ?>