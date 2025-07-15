<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen:</h1>
        </div>
      
      </div>
        <div class="tile">
            <h3 class="tile-title">Aprobar Pedidos Nuevos</h3>
            <div class="row mb-3">
                <div class="text-end col-md-6">
                     <div class="input-group mb-3 ">
                        <span class="input-group-text" id="almacen"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" aria-describedby="almacen" >
                        <button class="btn btn-info btn-sm">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Gestión</th>
                            <th>Fecha</th>
                            <th>Código</th>
                            <th>Funcionario</th>
                            <th>Actividad</th>
                            <th>Estado</th>
                            <th>Almacen</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</template>
<script setup>

</script>