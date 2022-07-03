<?php
    include_once 'config.php';
    include_once 'Functions/GenerateMC.php';
    include_once 'Functions/GenerateLC.php';
    include_once 'Functions/GenerateDAO.php';
    include_once 'Functions/GenerateL_TMP.php';
    include_once 'Functions/GenerateF_TMP.php';

    $showAlert = false;
    $showError = false;
    $errorLog = "";
    $successMsg = "";
    $tableNames = array();

    $inputFields = "";
    $validationFields = "";
    $descriptiveField = "";

    if (isset($_POST["btnConfirm"])) {

        if (isset($_POST["arrayVariables"])) {
            $inputFields = explode(",",$_POST["arrayVariables"]);
            $inputFields = str_replace(' ', '', $inputFields);
        }
        
        if (isset($_POST["validationFields"])) {
            $validationFields = explode(",",$_POST["validationFields"]);
            $validationFields = str_replace(' ', '', $validationFields);
        }

        if (isset($_POST["tableName"])) {
            $tableModel = new GetTableDesc();

            $TableData = $tableModel->getData($_POST["tableName"]);

            if ($TableData == false) {
                $showError = true;
                $errorLog = "Hay un problema con la tabla o no existe.";
            }else{
                $tableNames["pluralName"] = $_POST["pluralTableName"];
                $tableNames["singularName"] = $_POST["singularTableName"];
                $tableNames["tableName"] = $_POST["tableName"];
                $tableNames["descriptiveField"] = $_POST["descriptiveField"];

                writeOnFile_MainController($tableNames, $TableData, $inputFields, $validationFields);
                writeOnFile_ListController($tableNames, $TableData);
                writeOnFile_DAO($tableNames, $TableData);
                writeOnFile_ListTemplate($tableNames, $TableData);
                writeOnFile_FormTemplate($tableNames, $TableData, $inputFields, $validationFields);

                $showAlert = true;
                $successMsg = "Los archivos se crearon exitosamente! Puede encontrar los archivos en la carpeta raíz de este Sitio.";
            }
        }else{
            $showError = true;
            $errorLog = "El nombre de la tabla es requerido.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>File Generator</title>
</head>

<body>
    <main>
        <h1 class="text-center mt-5">MVC Scripts Generator</h1>
        <div class="d-flex justify-content-center mt-4">
            <img width="200px" src="https://cdn-icons-png.flaticon.com/512/7069/7069924.png" alt="icon.png"/>
        </div>

        <form action="main.php" method="post">

        <div style="margin-left: 15vh; margin-right:15vh; margin-top:8vh">

            <?php if ($showError) {?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $errorLog; ?>
                    </div>
            <?php } ?>

            <?php if ($showAlert) {?>
                <div class="alert alert-success mt-3" role="alert">
                    <?php echo $successMsg; ?>
                    </div>
            <?php } ?>

            <div class="form-group">
                <label for="tableName">Nombre de la tabla</label>
                <input type="text" class="form-control" id="tableName" name="tableName" placeholder="Pianos" value="<?php if (isset($_POST["tableName"])){echo $_POST["tableName"];}?>" required>
            </div>

            <div class="form-group" id="pluralNameDiv">
                <label for="pluralTableName">Nombre Plural para los archivos</label>
                <input type="text" class="form-control" id="pluralTableName" name="pluralTableName" value="<?php if (isset($_POST["pluralTableName"])){echo $_POST["pluralTableName"];}?>" placeholder="Pianos" required>
            </div>

            <div class="form-group" id="singularNameDiv">
                <label for="singularTableName">Nombre Singular para los archivos</label>
                <input type="text" class="form-control" id="singularTableName" name="singularTableName" value="<?php if (isset($_POST["singularTableName"])){echo $_POST["singularTableName"];}?>" placeholder="Piano" required>
            </div>
            
            <div class="form-group" id="arrayVariablesDiv" >
                <label for="arrayVariables">Campos que requieren un Select (Separados por coma)</label>
                <input type="text" class="form-control" id="arrayVariables" name="arrayVariables" value="<?php if (isset($_POST["arrayVariables"])){echo $_POST["arrayVariables"];}?>" placeholder="pianoest, pianocat">
            </div>
            
            <div class="form-group" id="descriptiveFieldDiv" >
                <label for="descriptiveField">Campo descriptivo (Utilizado para el modo DSP)</label>
                <input type="text" class="form-control" id="descriptiveField" name="descriptiveField" value="<?php if (isset($_POST["descriptiveField"])){echo $_POST["descriptiveField"];}?>" placeholder="pianodsc" required>
            </div>

            <div class="form-group" id="validationFieldsDiv" >
                <label for="validationFields">Campos que requieren validación "Not Empty" (Separados por coma). El sistema automáticamente tomará los campos con Null = Yes</label>
                <input type="text" class="form-control" id="validationFields" name="validationFields" value="<?php if (isset($_POST["validationFields"])){echo $_POST["validationFields"];}?>" placeholder="pianodsc, pianocat">
            </div>

            <div class="text-center mt-5">
                <button type="submit" name="btnConfirm" class="btn btn-primary mb-2">Generar</button>  <br>
            </div>

        </form>

        
    </main> 
    <footer class="text-center mt-5">
        <img src="https://firebasestorage.googleapis.com/v0/b/servientregasbd.appspot.com/o/poweredbyblack.png?alt=media&token=7299b84d-af75-456f-a9e5-36dce64dd672" width="400px" alt="">
    </footer>
</body>
</html>
