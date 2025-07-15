<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Partidas</h1>
        </div>
      
      </div>
        <div class="tile">
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addGroup"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="codigo">Código</option>
                            <option value="nompartida">Descripción</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerpartidas(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerpartidas(1, buscar, criterio)">
                            <i class="bi bi-search"></i> Buscar </button>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table" id="tabla-partidas">
                        <thead>
                        <tr class="table-secondary">
                            <th>#</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="partida in partidas" :key="partida">
                            <td>{{ partida.id }}</td>       
                            <td>{{ partida.codigo }}</td>
                            <td>{{ partida.nompartida }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updateGroup(partida)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="eliminarPartida(partida.id)">
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
                  <label class="form-label col-md-3">Código</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" placeholder="Ingrese código" v-model="codigo" required>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label class="form-label col-md-3">Descripción</label>
                  <div class="col-md-8">
                    <input class="form-control col-md-8" type="email" placeholder="ingrese descripción" v-model="nompartida" required>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <div v-if="errorResponsable" class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                    <li v-for="error in errorMostrarMsjResponsable" :key="error">{{ error }}</li>
                    </ul>
                </div>
              <div class="col-md-8 col-md-offset-3">
                <button type="button" class="btn btn-warning" @click="updatePartida" v-if="estadomodal==0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>Actualizar
                </button>
                <button type="button" class="btn btn-success" @click="savePartida" v-if="estadomodal==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
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
    const buscar = ref('');
    const criterio = ref('codigo');
    const modalpdf = ref(false);
    const pdf = ref('');
    const mostrarModal = ref(false);
    const codigo = ref('');
    const nompartida = ref('');
    const errorResponsable = ref(0);
    const estadomodal = ref(1); // 1 para nuevo, 0 para editar
    const tituloModal = ref('');
    const idpartida = ref(0);
    const errorMostrarMsjResponsable = ref([]);
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
    const obtenerpartidas = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/partidas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            partidas.value = response.data.partidas.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener los partidas:', error);
        }
    };
    const savePartida = async () => {
        try {
        if (validarPartida()) {return;}
            const response = await axios.post('/partidas/registrar', {
                codigo: codigo.value,
                nompartida: nompartida.value
            });
                swal.fire({
                    icon: 'info',
                    title: 'Exito!',
                    text: response.data.message,
                });
                mostrarModal.value = false;
                obtenerpartidas(1, '', '');
                codigo.value = '';
                nompartida.value = '';
        } catch (error){
            if (error.response.status === 422) {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.response.data.message,
                });
            console.log('Errores de validación:', error.response.data.errors);
            }
        }
    };
    const updatePartida = async () => {
        try {
            if (validarPartida()) {return;}
            const response = await axios.put('/partidas/actualizar', {
                id: idpartida.value,
                codigo: codigo.value,
                nompartida: nompartida.value
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
            mostrarModal.value = false;
            obtenerpartidas(1, '', '');
            codigo.value = '';
            nompartida.value = '';
        } catch (error) {
            console.error('Error al actualizar la partida:', error);
        }
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerpartidas(page,'','');
    };
    function addGroup() {
       mostrarModal.value = true;
       tituloModal.value = 'Nueva Partida'; 
       codigo.value = '';
       nompartida.value = '';
       estadomodal.value = 1; // Cambia a modo nuevo
    };
    function updateGroup(partida) {
        mostrarModal.value = true;
        tituloModal.value = 'Editar Partida';
        estadomodal.value = 0; // Cambia a modo editar
        idpartida.value = partida.id;
        codigo.value = partida.codigo;
        nompartida.value = partida.nompartida;
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function cerrarModal() {
        mostrarModal.value = false;
        codigo.value = '';
        nompartida.value = '';
        errorResponsable.value = 0;
        errorMostrarMsjResponsable.value = [];
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/partidas/pdf';
    };
    function validarPartida() {
        errorResponsable.value = 0
        errorMostrarMsjResponsable.value = []

        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/
        const validNumber = /^([0-9])*$/

        if (
            !nompartida.value ||
            !validText.test(nompartida.value)
        ) {
            errorMostrarMsjResponsable.value.push("Nombre de Partida vacío o incorrecto")
        }

        if (
            !codigo.value ||
            !validNumber.test(codigo.value)
        ) {
            errorMostrarMsjResponsable.value.push("Código vacío o incorrecto")
        }

        if (errorMostrarMsjResponsable.value.length) {
            errorResponsable.value = 1
        }

        return errorResponsable.value
    };
    const eliminarPartida = async (id) => {
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
            const response = await axios.delete(`/partidas/eliminar/${id}`)

            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerpartidas(1, '', '');
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
    obtenerpartidas(1,'','');
    });
</script>