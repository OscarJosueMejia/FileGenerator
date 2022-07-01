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
                $errorLog = "There is a problem with Table or it does not exists.";
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
                $successMsg = "Files Created Successfully.";
            }
        }else{
            $showError = true;
            $errorLog = "Table Name is required.";
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

            <div class="form-group">
                <label for="tableName">Table Name</label>
                <input type="text" class="form-control" id="tableName" name="tableName" placeholder="Pianos" required>
            </div>

            <div class="form-group" id="pluralNameDiv">
                <label for="pluralTableName">Plural Files Name</label>
                <input type="text" class="form-control" id="pluralTableName" name="pluralTableName" placeholder="Pianos" required>
            </div>

            <div class="form-group" id="singularNameDiv">
                <label for="singularTableName">Singular Files Name</label>
                <input type="text" class="form-control" id="singularTableName" name="singularTableName" placeholder="Piano" required>
            </div>
            
            <div class="form-group" id="arrayVariablesDiv" >
                <label for="arrayVariables">Fields that need a [select] input (Separated with comma)</label>
                <input type="text" class="form-control" id="arrayVariables" name="arrayVariables" placeholder="pianoest, pianocat">
            </div>
            
            <div class="form-group" id="descriptiveFieldDiv" >
                <label for="descriptiveField">Field destinated for name or description</label>
                <input type="text" class="form-control" id="descriptiveField" name="descriptiveField" placeholder="pianodsc" required>
            </div>

            <div class="form-group" id="validationFieldsDiv" >
                <label for="validationFields">Field that need a [not empty] validation (Separated with comma). System will automatically take Fields from table with Null = false</label>
                <input type="text" class="form-control" id="validationFields" name="validationFields" placeholder="pianodsc, pianocat">
            </div>

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

            <div class="text-center mt-5">
                <button type="submit" name="btnConfirm" class="btn btn-primary mb-2">Generate</button>       
            </div>

        </form>
    </main>    
</body>
</html>
