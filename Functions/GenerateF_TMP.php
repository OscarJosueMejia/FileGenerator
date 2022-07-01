<?php

function writeOnFile_FormTemplate($tableNames, $tableInfo,  $inputFields, $validationFields){

    $pluralName = $tableNames["pluralName"];
    $singularName = $tableNames["singularName"];

    $myfile = fopen("Generated_Scripts/".strtolower($singularName).".view.tpl", "w") or die("Unable to open file!");
    
    $fieldSets = "";

    $tablePK = "";
    foreach ($tableInfo as $key => $value) {
        
        if ($value["Key"] == 'PRI') {
            $tablePK = $value["Field"];
            if ($value["Extra"] != 'auto_increment') {
                $fieldSets .= '
                <fieldset class="row" style="border-color:transparent;">
                    <label class="col-4" for="'.$value["Field"].'">'.$value["Field"].'</label>
                    <input class="col-8" type="text" id="'.$value["Field"].'" name="'.$value["Field"].'" placeholder="Change this names/desc asap" value="{{'.$value["Field"].'}}"
                    {{if readonly}} readonly {{endif readonly}} style="border-radius: 0.4rem; border:none;"/>
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
        }else{

            if (in_array($value["Field"], $inputFields)) {
                $fieldSets .= '
                    <fieldset class="row" style="border-color:transparent;">
                        <label class="col-4" for="'.$value["Field"].'">'.$value["Field"].'</label>
                        <select class="col-8" name="'.$value["Field"].'" id="'.$value["Field"].'" {{if readonly}} readonly disabled {{endif readonly}} style="border-radius: 0.4rem; border:none;">
                            {{foreach '.$value["Field"].'Arr}}
                            <option value="{{value}}" {{selected}}>{{text}}</option>
                            {{endfor '.$value["Field"].'Arr}}
                        </select>
                    </fieldset>
                ';
            }else{
                $fieldSets .= '
                    <fieldset class="row" style="border-color:transparent;">
                        <label class="col-4" for="'.$value["Field"].'">'.$value["Field"].'</label>
                        <input class="col-8" type="text" id="'.$value["Field"].'" name="'.$value["Field"].'" placeholder="Change this names/desc asap" value="{{'.$value["Field"].'}}"
                        {{if readonly}} readonly {{endif readonly}} style="border-radius: 0.4rem; border:none;"/>
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
    <form action="index.php?page=mnt_'.strtolower($singularName).'" method="post" class="row offset-3 col-6"
    style="background-color:#F4F4F4; border-radius:1rem; padding:1rem; font-size:1.1rem">

        <input type="hidden" name="mode" value="{{mode}}" />
        <input type="hidden" name="crsf_token" value="{{crsf_token}}" />
        <input type="hidden" name="'.$tablePK.'" value="{{'.$tablePK.'}}" />

'.$fieldSets.'

        <fieldset >
            {{if showBtn}}
            <button type="submit" name="btnEnviar" 
                style="border-radius: 0.4rem; background-color:#ffce00; border-color:transparent; color:black;">{{btnEnviarText}}</button>
            &nbsp;
            {{endif showBtn}}
            <button name="btnCancelar" id="btnCancelar" style="border-radius: 0.4rem;">Cancelar</button>
        </fieldset>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("btnCancelar").addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = "index.php?page=mnt_'.strtolower($pluralName).'";
        });
    });
</script>';

    fwrite($myfile,$scriptContent);
    fclose($myfile);
}
?>