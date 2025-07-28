<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Unidades</h1>
        </div>
      
      </div>
        <div class="tile">
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addUnidad"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="nomunidad">Nombre</option>
                            <option value="sigla">Sigla</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenereUnidades(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenereUnidades(1, buscar, criterio)">
                            <i class="bi bi-search"></i> Buscar </button>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table" id="tabla-unidades">
                        <thead>
                        <tr class="table-secondary">
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Sigla</th>
                            <th>Partida</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="unidad in unidades" :key="unidad">
                            <td>{{ unidad.id }}</td>       
                            <td>{{ unidad.nomunidad }}</td>
                            <td>{{ unidad.sigla }}</td>
                            <td>{{ unidad.codigo_partida + ' - ' + unidad.partida }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updateUnidad(unidad)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="eliminarUnidad(unidad.id)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
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
    
    <div class="modal fade" :class="{'mostrar' : mostrarModal}" role="document" style="display: none;" data-target=".bd-example-modal-lg">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModal"></h4>
                <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                  <label class="form-label col-md-3">Partida</label>
                  <div class="col-md-8">
                    <select name="id_partida" id="" class="form-select" v-model="idpartida">
                      <option v-for="partida in partidas" :key="partida.id" :value="partida.id">
                        {{ partida.codigo + ' - ' + partida.nompartida }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label class="form-label col-md-3">Nombre</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" placeholder="Ingrese un nombre" v-model="nomunidad">
                  </div>
                </div>
                <div class="mb-3 row">
                  <label class="form-label col-md-3">Sigla</label>
                  <div class="col-md-8">
                    <input class="form-control col-md-8" type="text" placeholder="ingrese una sigla" v-model="sigla">
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <div v-if="errorUnidad" class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                    <li v-for="error in errorMostrarMsjUnidad" :key="error">{{ error }}</li>
                    </ul>
                </div>
              <div class="col-md-8 col-md-offset-3">
                <button type="button" class="btn btn-warning" @click="actualizarUnidad" v-if="estadomodal==0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>Actualizar
                </button>
                <button type="button" class="btn btn-success" @click="saveUnidad" v-if="estadomodal==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>Guardar
                </button>
                  &nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"  @click="cerrarModal"><i class="bi bi-x-circle-fill me-2"></i>Cancel</a>
              </div>
            </div>
        </div>
        </div>
    </div>
    </main>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
    const partidas = ref([]);
    const idpartida = ref(0);
    const unidades = ref([]);
    const buscar = ref('');
    const criterio = ref('nomunidad');
    const modalpdf = ref(false);
    const pdf = ref('');
    const mostrarModal = ref(false);
    // Variables para el modal de Partidas
    const nomunidad = ref('');
    const sigla = ref('');
    const errorUnidad = ref(0);
    const estadomodal = ref(1); // 1 para nuevo, 0 para editar
    const tituloModal = ref('');
    const idunidad = ref(0);
    const errorMostrarMsjUnidad = ref([]);
    const pagination = reactive({
        total: 0,
        current_page: 1,
        per_page: 10,
        last_page: 0,
        from: 0,
        to: 0,
    })
    const offset = 3;
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
    const obtenereUnidades = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/unidades?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            unidades.value = response.data.unidades.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener los Unidades:', error);
        }
    };
    const saveUnidad = async () => {
        try {
        if (validarUnidad()) {return;}
            const response = await axios.post('/unidades/registrar', {
                idpartida: idpartida.value,
                nomunidad: nomunidad.value,
                sigla: sigla.value
            });
                swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
                mostrarModal.value = false;
                obtenereUnidades(1, '', '');
                nomunidad.value = '';
                sigla.value = '';
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
    const actualizarUnidad = async () => {
        try {
            if (validarUnidad()) {return;}
            const response = await axios.put('/unidades/actualizar', {
                id: idunidad.value,
                idpartida: idpartida.value,
                nomunidad: nomunidad.value,
                sigla: sigla.value
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
            mostrarModal.value = false;
            obtenereUnidades(1, '', '');
            nomunidad.value = '';
            sigla.value = '';
        } catch (error) {
            console.error('Error al actualizar la Unidad:', error);
        }
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenereUnidades(page,'','');
    };
    function addUnidad() {
       obtenerPartidas();
       idpartida.value = 1; 
       mostrarModal.value = true;
       tituloModal.value = 'Nueva Unidad'; 
       nomunidad.value = '';
       sigla.value = '';
       estadomodal.value = 1; // Cambia a modo nuevo
    };
    const obtenerPartidas = async () => {
        try {
            const response = await axios.get('/partidas/todos');
            partidas.value = response.data;
        } catch (error) {
            console.error('Error al obtener las partidas:', error);
        }
    };
    function updateUnidad(unidad) {
        idpartida.value = unidad.idpartida;
        mostrarModal.value = true;
        tituloModal.value = 'Editar Unidad';
        estadomodal.value = 0; // Cambia a modo editar
        idunidad.value = unidad.id;
        nomunidad.value = unidad.nomunidad;
        sigla.value = unidad.sigla;
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function cerrarModal() {
        idpartida.value = 0;
        mostrarModal.value = false;
        nomunidad.value = '';
        sigla.value = '';
        errorUnidad.value = 0;
        errorMostrarMsjUnidad.value = [];
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/unidades/pdf';
    };
    function validarUnidad() {
        errorUnidad.value = 0
        errorMostrarMsjUnidad.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/
        if (
            !nomunidad.value ||
            !validText.test(nomunidad.value)
        ) {
            errorMostrarMsjUnidad.value.push("Nombre de la Unidad vacío o incorrecto")
        }

        if (
            !sigla.value ||
            !validText.test(sigla.value)
        ) {
            errorMostrarMsjUnidad.value.push("Sigla vacía o incorrecta")
        }

        if (errorMostrarMsjUnidad.value.length) {
            errorUnidad.value = 1
        }

        return errorUnidad.value
    };
    const eliminarUnidad = async (id) => {
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
            const response = await axios.delete(`/unidades/eliminar/${id}`)

            await Swal.fire({
                icon: 'info',
                title: '',
                text: response.data.message,
            });
            obtenereUnidades(1, '', '');
            }

        } catch (error) {
            console.error('Error al eliminar la partida:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar la partida',
            });
        }
        };
    onMounted(() => {
    obtenereUnidades(1,'','');
    });
</script>