<div>
    <!-- Lista de todos los moderadores -->
    <div>
        <h2>Lista de Moderadores</h2>
        <table width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Laboratorio</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($get('moderadores') as $moderador) {
                    $laboratorio = $moderador->getLaboratorio();
                    echo <<<TAG
                    <tr>
                    <td>{$moderador->id}</td>
                    <td>{$moderador->nombre}</td>
                    <td>{$laboratorio->nombre} ({$laboratorio->id})</td>
                    </tr>
TAG;
                }
            ?>
            </tbody>
        </table>
    </div>
    <div>
        <h2>Agregar nuevo moderador</h2>
        <form action="/admin/moderadores/nuevo" method="post">
            <label for="id">ID</label>
            <br>
            <input type="text" name="id" id="id">
            <br>
            <label for="laboratorio_id">Laboratorio ID</label>
            <br>
            <input type="text" name="laboratorio_id" id="laboratorio_id">
            <br>
            <input type="submit" value="Agregar">
        </form>
    </div>
</div>
