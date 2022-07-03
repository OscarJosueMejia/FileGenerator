<?php
/**
 * PHP Version 7
 * List View Template Script - File Generator
 *
 * @category Functions 
 * @author   Oscar Josue Mejia Seren 
 * @version 1.0
 */

function writeOnFile_ListTemplate($tableNames, $tableInfo){

    $pluralName = $tableNames["pluralName"];
    $singularName = strtolower($tableNames["singularName"]);
    $descriptiveField = $tableNames["descriptiveField"];

    $myfile = fopen("Generated_Scripts/".strtolower($pluralName).".view.tpl", "w") or die("Unable to open file!");
    
    $tablePK = "";
    foreach ($tableInfo as $key => $value) {
        if ($value["Key"] == 'PRI') {
            $tablePK = $value["Field"];
        }
    }

    $headerList = "";
    $rowList = "";

    foreach ($tableInfo as $key => $value) {
        $headerList .= "<th>".$value["Field"]."</th>\n";

        if ($value["Field"] == $descriptiveField) {
            $rowList .= '<td><a href="index.php?page=mnt_'.strtolower($singularName).'&mode=DSP&id={{'.$tablePK.'}}">{{'.$descriptiveField.'}}</td>'."\n";
        }else{
            $rowList .= "<td>{{".$value["Field"]."}}</td>\n";
        }
        
    }

    $scriptContent = 
    '
<h1 class="center">Trabajar con '.$pluralName.'</h1>
<section>

</section>
<section class="row flex-center"
    style="background-color:#f4f4f4;margin-top: 1rem;border-radius:1rem; padding-top:1rem; padding-bottom:1rem;">
    <table>
        <thead>
            <tr>
        '.$headerList.'
                <th><a href="index.php?page=mnt_'.$singularName.'&mode=INS">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach '.$pluralName.'}}
            <tr>
                '.$rowList.'
                <td>
                    <a href="index.php?page=mnt_'.$singularName.'&mode=UPD&id={{'.$tablePK.'}}">Editar</a>
                    &NonBreakingSpace;
                    <a href="index.php?page=mnt_'.$singularName.'&mode=DEL&id={{'.$tablePK.'}}">Eliminar</a>
                </td>
            </tr>
            {{endfor '.$pluralName.'}}
        </tbody>
    </table>
</section>';

    fwrite($myfile,$scriptContent);
    fclose($myfile);
}
?>