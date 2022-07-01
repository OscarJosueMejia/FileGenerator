<?php

function writeOnFile_ListTemplate($tableNames, $tableInfo){

    $pluralName = $tableNames["pluralName"];


    $myfile = fopen("Generated_Scripts/".strtolower($pluralName).".view.tpl", "w") or die("Unable to open file!");
    
    $headerList = "";
    $rowList = "";

    foreach ($tableInfo as $key => $value) {
        $headerList .= "<th>".$value["Field"]."</th>\n";
        $rowList .= "<td>{{".$value["Field"]."}}</td>\n";
    }

    $scriptContent = 
    '
<h1>Trabajar con '.$pluralName.'</h1>
<section>

</section>
<section>
    <table>
        <thead>
            <tr>
                '.$headerList.'
                <th><a href="index.php?page=Mnt-Piano&mode=INS">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach '.$pluralName.'}}
            <tr>
                '.$rowList.'
                <td>
                    <a href="index.php?page=Mnt-Piano&mode=UPD&id={{pianoid}}">Editar</a>
                    &NonBreakingSpace;
                    <a href="index.php?page=Mnt-Piano&mode=DEL&id={{pianoid}}">Eliminar</a>
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