<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      
      </div>
        <div class="tile">
            <h3 class="tile-title">Personal</h3>
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addPersonal"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="ciper">Carnet</option>
                            <option value="nomper">Nombre</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerPersonal(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerPersonal(1, buscar, criterio)">
                            <i class="bi bi-search"></i> Buscar </button>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table" id="tabla-provedores">
                        <thead>
                        <tr class="table-secondary">
                            <th>#</th>
                            <th>Código</th>
                            <th>Grado y Nombre</th>
                            <th>Carnet</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            <th>Dirección</th>
                            <th>Almacen</th>   
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="persona in personal" :key="persona.id">
                            <td>{{ persona.id }}</td>
                            <td>{{ persona.codper }}</td>
                            <td>{{ persona.nomper }}</td>
                            <td>{{ persona.ciper }}</td>
                            <td>{{ persona.telper }}</td>
                            <td>{{ persona.emailper }}</td>
                            <td>{{ persona.cargo }}</td>
                            <td>{{ persona.dirper }}</td>
                            <td>{{ persona.almacen }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updatePersonal(persona)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deletePersonal(persona.id)">
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
    
    <div class="modal fade modal-lg" :class="{'mostrar' : mostrarModal}" role="document" style="display: none;" data-target=".bd-example-modal-lg">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModal"></h4>
                <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="" class="form-label">Código</label>
                        <div class="input-group">
                            <span class="input-group-text" id="codper"><i class="bi bi-upc"></i></span>
                            <input type="text" class="form-control" aria-describedby="codper" v-model="codper" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <label for="" class="form-label">Grado y Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text" id="nomper"><i class="bi bi-person-fill-add"></i></span>
                            <input type="text" class="form-control" aria-describedby="nomper" v-model="nomper" placeholder="Ingrese un Nombre">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                         <label for="" class="form-label">Telefóno</label>
                    <div class="input-group">
                        <span class="input-group-text" id="telefono"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control" aria-describedby="telefono" v-model="telper" placeholder="Ingrese un Teléfono"> 
                    </div>
                    </div>
                    <div class="col-md-6">
                         <label for="" class="form-label">Carnet</label>
                        <div class="input-group">
                            <span class="input-group-text" id="carnet"><i class="bi bi-123"></i></span>
                            <input type="text" class="form-control" aria-describedby="carnet" v-model="ciper" placeholder="Ingrese un Carnet">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                         <label for="" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text" id="email"><i class="bi bi-envelope"></i></span>
                        <input type="text" class="form-control" aria-describedby="email" v-model="emailper" placeholder="Ingrese un e-mail"> 
                    </div>
                    </div>
                    <div class="col-md-6">
                         <label for="" class="form-label">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text" id="direccion"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" aria-describedby="direccion" v-model="dirper" placeholder="Ingrese una Dirección">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                  <label for="" class="form-label">Cargo</label>
                    <div class="input-group">
                        <span class="input-group-text" id="obs"><i class="bi bi-list-ul"></i></span>
                        <input type="text" class="form-control" v-model="cargo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div v-if="errorPersonal" class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                    <li v-for="error in errorMostrarMsjPersonal" :key="error">{{ error }}</li>
                    </ul>
                </div>
              <div class="col-md-8 col-md-offset-3">
                <button type="button" class="btn btn-warning" @click="actualizarPersonal" v-if="estadomodal==0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>Actualizar
                </button>
                <button type="button" class="btn btn-success" @click="guardarPersonal" v-if="estadomodal==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
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
    const personal = ref([]);
    const buscar = ref('');
    const criterio = ref('ciper');
    const modalpdf = ref(false);
    const pdf = ref('');
    const mostrarModal = ref(false);
    // Variables para el modal de Partidas
    const codper = ref('');
    const nomper = ref('');
    const ciper = ref('');
    const telper = ref('');
    const emailper = ref('');
    const dirper = ref('');
    const id_almacen = ref(0);
    const tituloAlmacen = ref('');
    const cargo = ref('');

    const errorPersonal = ref(0);
    const estadomodal = ref(1); // 1 para nuevo, 0 para editar
    const tituloModal = ref('');
    const idpersonal = ref(0);
    const errorMostrarMsjPersonal = ref([]);
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
     const obtenertitulo = async () => {
        try {
            const response = await axios.get('/almacen/titulo');
            tituloAlmacen.value = response.data.nomalmacen || '';
        } catch (error) {
            console.error('Error al obtener el título del almacén:', error);
        }   
    };
    const obtenerPersonal = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/personal?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            personal.value = response.data.personal.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener los Personal:', error);
        }
    };
    const obtenerCodigo = async () => {
        try {
            const response = await axios.get('/personal/codigo');
            codper.value = response.data.codigo;
        } catch (error) {
            console.error('Error al obtener el código:', error);
        }
    };
    const guardarPersonal = async () => {
        try {
        if (validarPersonal()) {return;}
            const response = await axios.post('/personal/registrar', {
                codper: codper.value,
                nomper: nomper.value,
                ciper: ciper.value,
                telper: telper.value,
                emailper: emailper.value,
                dirper: dirper.value,
                cargo : cargo.value,
                id_almacen: id_almacen.value
            });
                swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
                mostrarModal.value = false;
                obtenerPersonal(1, '', '');
                codper.value = '';
                nomper.value = '';
                ciper.value = '';
                telper.value = '';
                emailper.value = '';
                cargo.value = '';
                dirper.value = '';
                errorPersonal.value = 0;
                errorMostrarMsjPersonal.value = [];
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
    const actualizarPersonal = async () => {
        try {
            if (validarPersonal()) {return;}
            const response = await axios.put('/personal/actualizar', {
                id: idpersonal.value,
                nomper: nomper.value,
                dirper: dirper.value,
                telper: telper.value,
                ciper: ciper.value,
                emailper: emailper.value,
                cargo : cargo.value,
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
            mostrarModal.value = false;
            obtenerPersonal(1, '', '');
            nomper.value = '';
            ciper.value = '';
            telper.value = '';
            emailper.value = '';
            dirper.value = '';
            errorPersonal.value = 0;
            cargo.value='';
            errorMostrarMsjPersonal.value = [];
        } catch (error) {
            console.error('Error al actualizar la personal:', error);
        }
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerPersonal(page,'','');
    };
    function addPersonal() {
        mostrarModal.value = true;
        tituloModal.value = 'Nuevo Personal';
        idpersonal.value = 0;
        obtenerCodigo();
        nomper.value = '';
        ciper.value = '';
        telper.value = '';
        emailper.value = '';
        dirper.value = '';
        cargo.value = '';
        estadomodal.value = 1; // Cambia a modo nuevo
        errorPersonal.value = 0;
        errorMostrarMsjPersonal.value = [];
    };
    function updatePersonal(personal) {
        mostrarModal.value = true;
        tituloModal.value = 'Editar Personal';
        estadomodal.value = 0; // Cambia a modo editar
        idpersonal.value = personal.id;
        codper.value = personal.codper;
        nomper.value = personal.nomper;
        ciper.value = personal.ciper;
        telper.value = personal.telper;
        emailper.value = personal.emailper;
        dirper.value = personal.dirper;
        cargo.value = personal.cargo;
        id_almacen.value = personal.id_almacen;
        errorPersonal.value = 0;
        errorMostrarMsjPersonal.value = [];
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function cerrarModal() {
        mostrarModal.value = false;
        codper.value = '';
        nomper.value = '';
        ciper.value = '';
        telper.value = '';
        emailper.value = '';
        dirper.value = '';
        id_almacen.value = 0;
        errorPersonal.value = 0;
        cargo.value = '';
        errorMostrarMsjPersonal.value = [];
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/personal/pdf';
    };
    function validarPersonal() {
        errorPersonal.value = 0
        errorMostrarMsjPersonal.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/
        const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        const validnumber = /^[0-9]{6,15}$/
        if (
            !nomper.value ||
            !validText.test(nomper.value)
        ) {
            errorMostrarMsjPersonal.value.push("Nombre del Personal esta vacío o incorrecto")
        }
        if (
            !dirper.value ||
            !validText.test(dirper.value)
        ) {
            errorMostrarMsjPersonal.value.push("Dirección vacía o incorrecta")
        }
        if (
            !telper.value ||
            !validnumber.test(telper.value)
        ) {
            errorMostrarMsjPersonal.value.push("Teléfono vacío o incorrecto")
        }
        if (
            !ciper.value ||
            !validnumber.test(ciper.value)
        ) {
            errorMostrarMsjPersonal.value.push("carnet vacío o incorrecto")
        }
        if (
            !emailper.value ||
            !validEmail.test(emailper.value)
        ) {
            errorMostrarMsjPersonal.value.push("E-mail vacío o incorrecto")
        }
         if (
            !cargo.value ||
            !validText.test(cargo.value)
        ) {
            errorMostrarMsjPersonal.value.push("el Cargo esta vacío o incorrecto")
        }
        if (errorMostrarMsjPersonal.value.length) {
            errorPersonal.value = 1
        }

        return errorPersonal.value
    };
    const deletePersonal = async (id) => {
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
            const response = await axios.delete(`/personal/eliminar/${id}`)

            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerPersonal(1, '', '');
            }

        } catch (error) {
            console.error('Error al eliminar la provedor:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar la provedor',
            });
        }
        };
    onMounted(() => {
        obtenertitulo();
        obtenerPersonal(1,'','');
    });
</script>