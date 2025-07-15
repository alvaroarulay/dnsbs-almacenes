<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      
      </div>
        <div class="tile">
            <h3 class="tile-title">Articulos</h3>
            <template v-if="listado == 0">
                <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addArticulo"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="codigo">Código</option>
                            <option value="descripcion">Descripción</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerArticulos(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerArticulos(1, buscar, criterio)">
                            <i class="bi bi-search"></i> Buscar </button>
                    </div>
                </div>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table" id="tabla-articulos">
                            <thead>
                            <tr class="table-secondary">
                                <th>#</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Partida</th>
                                <th>Almacen</th>  
                                <th>Fecha de Expiración (dias restantes)</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulo in articulos" :key="articulo" v-if="articulos.length!=0">
                                    <td>{{ articulo.id }}</td> 
                                    <td>{{ articulo.codigo }}</td>
                                    <td>{{ articulo.descripcion }}</td>
                                    <td>{{ articulo.unidad_nombre }}</td>
                                    <td>{{ articulo.partida_nombre }}</td>
                                    <td>{{ articulo.almacen_nombre }}</td>
                                    <td> 
                                        <div v-if="obtenerdias(articulo) == 'No aplica'">
                                            <span class="me-1 badge badge-pill bg-secondary">{{ obtenerdias(articulo) }}</span>
                                        </div>
                                        <div v-else-if="obtenerdias(articulo) > 41">
                                            <span class="me-1 badge badge-pill bg-success">{{ obtenerdias(articulo)}}</span>
                                        </div>
                                        <div v-else-if="obtenerdias(articulo) > 21 && obtenerdias(articulo) <= 40">
                                            <span class="me-1 badge badge-pill bg-warning">{{ obtenerdias(articulo) }}</span>
                                        </div>
                                        <div v-else>
                                            <span class="me-1 badge badge-pill bg-danger">{{ obtenerdias(articulo) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning" @click="updateArticulo(articulo)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" @click="deleteArticulo(articulo.id)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-else >
                                    <td colspan="8">No hay articulos en este almacen</td>
                                </tr>
                            </tbody>
                        </table>
                    <nav v-if="pagination.total > pagination.per_page">
                            <ul class="pagination">
                                <li class="page-item"
                                v-for="page in pagesNumber" 
                                :key="page" 
                                :class="{ active: page === pagination.current_page }"
                                >
                                <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </template>
            <template v-else>
               <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card border-warning mb-3">
                        <div class="card-header text-bg-warning mb-3">Partidas</div>

                        <div class="card-body">
                            <label for="" class="form-label">Seleccione una Partida</label>
                                <div class="input-group mb-3 ">
                                    <span class="input-group-text" id="partida"><i class="bi bi-list"></i></span>
                                    <select name="" aria-describedby="partida" class="form-select" v-model="partidaSeleccionada"  @change="onChangePartida($event)">
                                        <option value="0">Seleccione...</option>
                                        <option v-for="partida in partidas" :key="partida.id" :value="partida.id">
                                            {{partida.codigo +'-'+ partida.nompartida }}
                                        </option>
                                    </select>
                                </div>
                                <label for="" class="form-label">Partida Seleccionada</label>
                                <div class="input-group mb-3 ">
                                    <span class="input-group-text" id="partida"><i class="bi bi-check2-circle"></i></span>
                                    <input type="text" class="form-control" aria-describedby="partida" v-model="nompartida" disabled>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info mb-3">
                        <div class="card-header text-bg-info mb-3">Unidades</div>
                        <div class="card-body">
                                <label for="" class="form-label">Seleccione Unidad</label>
                                <div class="input-group mb-3 ">
                                    <span class="input-group-text" id="unidad"><i class="bi bi-list"></i></span>
                                    <select name="" aria-describedby="unidad" class="form-select" v-model="unidadseleccionada"  @change="onChangeUnidad($event)">
                                        <option value="0">Seleccione...</option>
                                        <option v-for="unidad in unidades" :key="unidad.id" :value="unidad.id">
                                            {{ unidad.nomunidad }}
                                        </option>
                                    </select>
                                </div>
                                <label for="" class="form-label">Unidad Seleccionada</label>
                                <div class="input-group mb-3 ">
                                    <span class="input-group-text" id="unidad"><i class="bi bi-check2-circle"></i></span>
                                    <input type="text" class="form-control" aria-describedby="unidad" v-model="nomunidad" disabled>
                                </div>
                        </div>
                    </div>
                </div>
               </div>
               <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card border-primary mb-3">
                        <div class="card-header text-bg-primary mb-3">
                            Articulo
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Código</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="codigo"><i class="bi bi-upc"></i></span>
                                        <input type="text" class="form-control" aria-describedby="codigo" v-model="codigo">
                                    </div>
                                </div>
                                <div class="col-md-6" v-if="partidaSeleccionada==6">
                                    <label for="" class="form-label">Fecha de Expiración</label>
                                    <div class="input-group mb-3 ">
                                        <span class="input-group-text" id="codigo"><i class="bi bi-upc"></i></span>
                                        <input type="date" class="form-control" aria-describedby="codigo" v-model="fechaExpiracion">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                  <label for="" class="form-label">Descripción</label>
                            <div class="input-group mb-3 ">
                                <span class="input-group-text" id="descripcion"><i class="bi bi-bag"></i></span>
                                <input type="text" class="form-control" aria-describedby="descripcion" v-model="descripcion">
                            </div>
                            </div>
                            <div v-if="errorArticulo" class="alert alert-danger">
                                <strong>Por favor corrige los siguientes errores:</strong>
                                <ul>
                                <li v-for="error in errorMostrarMsjArticulo" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer text-center d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-success me-md-2" type="button" v-if="newArticulo==1" @click="guardarArticulo"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg>Guardar</button>
                            <button class="btn btn-success me-md-2" type="button" v-else-if="editArticulo==1" @click="actualizarArticulo"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg>Actualizar</button>
                            <button class="btn btn-danger" type="button" @click="cancelar"><i class="bi bi-x-square"></i>Cancelar</button>
                        </div>
                    </div>
                </div>  
               </div>
            </template>
        </div>
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalpdf}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" data-target=".bd-example-modal-lg">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Documento Preliminar</h4>
                        <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModalpdf()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <iframe
                            :src="pdf"
                            frameBorder="0"
                            scrolling="auto"
                            height="768"
                            width="1024"
                        ></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModalpdf()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
  
    </main>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
    const tituloAlmacen= ref('');
    const listado = ref([0]);
    const articulos = ref([]);
    const buscar = ref('');
    const criterio = ref('codigo');
    const modalpdf = ref(false);
    const pdf = ref('');
    const codanterior = ref('');
    const codigo = ref(''); 
    const descripcion = ref('');
    const newArticulo = ref(0);
    const editArticulo = ref(0);
    const fechaExpiracion = ref('');

    // Variables para el modal de Partidas
    const partidas = ref([]);
    const partidaSeleccionada = ref(0);
    const nompartida = ref('');
    const unidades = ref([]);
    const unidadseleccionada = ref(0);
    const nomunidad = ref('');

    const errorArticulo = ref(0);
    const idarticulo = ref(0);
    const errorMostrarMsjArticulo = ref([]);
    const pagination = reactive({
        total: 0,
        current_page: 1,
        per_page: 10,
        last_page: 0,
        from: 0,
        to: 0,
    })
    const offset = 3;
    const obtenertitulo = async () => {
        try {
            const response = await axios.get('/almacen/titulo');
            tituloAlmacen.value = response.data.nomalmacen || '';
        } catch (error) {
            console.error('Error al obtener el título del almacén:', error);
        }   
    };
    const pagesNumber = computed(() => {
        if (!pagination.to) return []

        let from = pagination.current_page - offset
        if (from < 1) from = 1

        let to = from + offset * 2
        if (to >= pagination.last_page) to = pagination.last_page

        const pages = []
        for (let i = from; i <= to; i++) {
            pages.push(i)
        }
        return pages
        });
    const obtenerArticulos = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/articulos?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            articulos.value = response.data.articulos.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener los Articulos:', error);
        }
    };
    const guardarArticulo = async () => {
        try {
        if (validarArticulo()) {return;}
            const response = await axios.post('/articulos/registrar', {
                codanterior: codanterior.value,
                codigo: codigo.value,
                descripcion: descripcion.value,
                fecha_expiracion: fechaExpiracion.value,
                partida_id: partidaSeleccionada.value,
                unidad_id: unidadseleccionada.value
            });
                swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
                obtenerArticulos(1, '', '');
                listado.value = 0;
              reset();
        } catch (error) {
             if (error.response.status === 422) {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.response.data.message,
                });
            }
        }
    };
    const actualizarArticulo = async () => {
        try {
            if (validarArticulo()) {return;}
            const response = await axios.put('/articulos/actualizar', {
                id: idarticulo.value,
                codanterior: codanterior.value,
                codigo: codigo.value,
                descripcion: descripcion.value,
                fecha_expiracion: fechaExpiracion.value,
                partida_id: partidaSeleccionada.value,
                unidad_id: unidadseleccionada.value
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
                obtenerArticulos(1, '', '');
                idarticulo.value = 0;
                listado.value = 0;
                reset();
        } catch (error) {
            console.error('Error al actualizar la Articulo:', error);
        }
    };

    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerArticulos(page,'','');
    };
    function addArticulo() {
       listado.value=1;
        newArticulo.value = 1;
        editArticulo.value = 0;
       reset();
    };
    function obtenerdias(articulo) {
        if (articulo.fecha_expiracion!== null) {
            const fechaCreacion = new Date(articulo.created_at);
            const fechaExpiracion = new Date(articulo.fecha_expiracion);
            const diferenciaTiempo = fechaExpiracion - fechaCreacion;
            const diasRestantes = Math.round(diferenciaTiempo / (1000 * 60 * 60 * 24));
            return diasRestantes >= 0 ? diasRestantes : 'Expirado';
        }
        return 'No aplica';
    };
    function updateArticulo(articulo) {
        listado.value=1;
        newArticulo.value = 0;
        editArticulo.value = 1;
        idarticulo.value = articulo.id;
        codanterior.value = articulo.cod_anterior;
        codigo.value = articulo.codigo;
        descripcion.value = articulo.descripcion;
        nompartida.value = articulo.partida_nombre;
        nomunidad.value = articulo.unidad_nombre;
        partidaSeleccionada.value = articulo.id_partida;
        unidadseleccionada.value = articulo.id_unidad;
        fechaExpiracion.value = new Date(articulo.fecha_expiracion).toISOString().slice(0, 10) || '';
        obtenerUnidades(partidaSeleccionada.value);
    };
    function cancelar() {
        listado.value=0;
        newArticulo.value = 0;
        editArticulo.value = 0;
        reset();
    };
    function reset(){
        nompartida.value='';
        nomunidad.value='';
        partidaSeleccionada.value = 0;
        unidadseleccionada.value = 0;
        errorArticulo.value = 0;
        errorMostrarMsjArticulo.value = [];
        codanterior.value = '';
        codigo.value = '';
        descripcion.value = '';
        fechaExpiracion.value = '';
    }
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/articulos/pdf';
    };
    function validarArticulo() {
        errorArticulo.value = 0
        errorMostrarMsjArticulo.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/

        if (
            !codanterior.value ||
            !validText.test(codanterior.value)
        ) {
            errorMostrarMsjArticulo.value.push("el Código esta vacío o incorrecto")
        }
        if (
            !codigo.value ||
            !validText.test(codigo.value)
        ) {
            errorMostrarMsjArticulo.value.push("el Código esta vacío o incorrecto")
        }
        if (
            !descripcion.value ||
            !validText.test(descripcion.value)
        ) {
            errorMostrarMsjArticulo.value.push("la Descripción esta vacía o incorrecta")
        }
        if (partidaSeleccionada.value == 0) {
            errorMostrarMsjArticulo.value.push("Seleccione una Partida")
        }
        if (unidadseleccionada.value == 0) {
            errorMostrarMsjArticulo.value.push("Seleccione una Unidad")
        }
        if (errorMostrarMsjArticulo.value.length) {
            errorArticulo.value = 1
        }

        return errorArticulo.value
    };
    const deleteArticulo = async (id) => {
        try {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, borrar!',
                cancelButtonText: 'Cancelar',
            });

            if (result.isConfirmed) {
            const response = await axios.delete(`/articulos/eliminar/${id}`)

            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerArticulos(1, '', '');
            }

        } catch (error) {
            console.error('Error al eliminar la Articulo:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar el Articulo',
            });
        }
        };
    const obtenerPartidas = async () => {
        try {
            const response = await axios.get('/partidas/todos');
            partidas.value = response.data;
        } catch (error) {
            console.error('Error al obtener las partidas:', error);
        }
    };
    const onChangePartida = (event) => {
        partidaSeleccionada.value = event.target.value;
        if (partidaSeleccionada.value != 0) {
            nompartida.value = partidas.value.find(partida => partida.id == partidaSeleccionada.value).nompartida;
        } else {
            nompartida.value = '';
        }
        if(newArticulo.value==1){
            cantidadpartida(event.target.value);
            obtenerUnidades(partidaSeleccionada.value);
        }
        if(editArticulo.value==1){
            unidadseleccionada.value = 0;
            cantidadpartida(event.target.value);
        }
    };  
     function cantidadpartida(id){

        var url= '/articulos/partidas/'+id;

        axios.get(url).then((response)=>{
            codigo.value = response.data.partidas;
            codanterior.value = response.data.codanterior;
        })
        .catch(function (error) {
            console.log(error);
        });
    }
    const obtenerUnidades = async () => {
        try {
            const response = await axios.get('/unidades/todos/'+partidaSeleccionada.value);
            unidades.value = response.data;
        } catch (error) {
            console.error('Error al obtener las unidades:', error);
        }
    };
    const onChangeUnidad = (event) => {
        unidadseleccionada.value = event.target.value;
        if (unidadseleccionada.value != 0) {
            nomunidad.value = unidades.value.find(unidad => unidad.id == unidadseleccionada.value).nomunidad;
        } else {
            nomunidad.value = '';
        }
    };

    onMounted(() => {
        obtenertitulo();
        obtenerArticulos(1,'','');
        obtenerPartidas();
    });
</script>