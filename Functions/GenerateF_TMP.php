<?php

function writeOnFile_FormTemplate($tableNames, $tableInfo,  $inputFields, $validationFields){

    $pluralName = $tableNames["pluralName"];
    $singularName = $tableNames["singularName"];

    $myfile = fopen("Generated_Scripts/".strtolower($singularName).".view.tpl", "w") or die("Unable to open file!");
    
    $headerList = "";
    $rowList = "";
    $fieldSets = "";

    $tablePK = "";
    foreach ($tableInfo as $key => $value) {
        $headerList .= "<th>".$value["Field"]."</th>\n";
        $rowList .= "<td>{{".$value["Field"]."}}</td>\n";
        
        if ($value["Key"] == 'PRI') {
            $tablePK = $value["Field"];
        }else{

            if (in_array($value["Field"], $inputFields)) {
                $fieldSets .= '
                    <fieldset>
                        <label for="'.$value["Field"].'">'.$value["Field"].'</label>
                        <select name="'.$value["Field"].'" id="'.$value["Field"].'" {{if readonly}} readonly disabled {{endif readonly}}>
                            {{foreach '.$value["Field"].'Arr}}
                            <option value="{{value}}" {{selected}}>{{text}}</option>
                            {{endfor '.$value["Field"].'Arr}}
                        </select>
                    </fieldset>
                ';
            }else{
                $fieldSets .= '
                    <fieldset>
                        <label for="'.$value["Field"].'">'.$value["Field"].'</label>
                        <input type="text" id='.$value["Field"].' name='.$value["Field"].' placeholder="Change this names/desc asap" value="{{'.$value["Field"].'}}"
                        {{if readonly}} readonly {{endif readonly}} />
                ';
    
                if (in_array($value["Field"], $validationFields)) {
                    $fieldSets .= '
                        {{if error_'.$value["Field"].'}} {{foreach error_'.$value["Field"].'}} <div class="error">{{this}}</div>
                        {{endfor error_'.$value["Field"].'}}
                        {{endif error_'.$value["Field"].'}}
                    ';
                }
                $fieldSets .= '</fieldset>';
            }
        }

    }

    




    $scriptContent = 
    '
<h1>{{mode_desc}}</h1>
<section>
    <form action="index.php?page=mnt_'.strtolower($singularName).'" method="post">
        <input type="hidden" name="mode" value="{{mode}}" />
        <input type="hidden" name="crsf_token" value="{{crsf_token}}" />
        <input type="hidden" name="'.$tablePK.'" value="{{'.$tablePK.'}}" />




        <fieldset>
            {{if showBtn}}
            <button type="submit" name="btnEnviar">{{btnEnviarText}}</button>
            &nbsp;
            {{endif showBtn}}
            <button name="btnCancelar" id="btnCancelar">Cancelar</button>
        </fieldset>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("btnCancelar").addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = "index.php?page=mnt_pianos";
        });
    });
</script>';

    fwrite($myfile,$scriptContent);
    fclose($myfile);
}
?>