
<h1>Trabajar con Pianos</h1>
<section>

</section>
<section class="row flex-center"
    style="background-color:#f4f4f4;margin-top: 1rem;border-radius:1rem; padding-top:1rem; padding-bottom:1rem;">
    <table>
        <thead>
            <tr>
        <th>pianoid</th>
<th>pianodsc</th>
<th>pianobio</th>
<th>pianosales</th>
<th>pianoimguri</th>
<th>pianoimgthb</th>
<th>pianoprice</th>
<th>pianoest</th>

                <th><a href="index.php?page=mnt_piano&mode=INS">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach Pianos}}
            <tr>
                <td>{{pianoid}}</td>
<td><a href="index.php?page=mnt_piano&mode=DSP&id={{pianoid}}">{{pianodsc}}</td>
<td>{{pianobio}}</td>
<td>{{pianosales}}</td>
<td>{{pianoimguri}}</td>
<td>{{pianoimgthb}}</td>
<td>{{pianoprice}}</td>
<td>{{pianoest}}</td>

                <td>
                    <a href="index.php?page=mnt_piano&mode=UPD&id={{pianoid}}">Editar</a>
                    &NonBreakingSpace;
                    <a href="index.php?page=mnt_piano&mode=DEL&id={{pianoid}}">Eliminar</a>
                </td>
            </tr>
            {{endfor Pianos}}
        </tbody>
    </table>
</section>