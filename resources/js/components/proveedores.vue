<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Proveedores</h1>
        </div>
      
      </div>
        <div class="tile">
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addProvedor"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="nompro">Nombre</option>
                            <option value="direccion">Dirección</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerProvedores(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerProvedores(1, buscar, criterio)">
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
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Nit</th>
                            <th>Email</th>
                            <th>Contacto</th>
                            <th>Observaciones</th>   
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="provedor in provedores" :key="provedor">
                            <td>{{ provedor.id }}</td>       
                            <td>{{ provedor.nompro }}</td>
                            <td>{{ provedor.direccion }}</td>
                            <td>{{ provedor.telefono }}</td>
                            <td>{{ provedor.nit }}</td>
                            <td>{{ provedor.email }}</td>
                            <td>{{ provedor.contacto }}</td>
                            <td>{{ provedor.observaciones }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updateProvedor(provedor)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deleteProvedor(provedor.id)">
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
                <div class="mb-3 row">
                  <label for="" class="form-label">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-folder-plus"></i></span>
                        <input type="text" class="form-control" aria-describedby="establecimiento" v-model="nompro" placeholder="Ingrese un Nombre" >
                    </div>
                </div>
                <div class="mb-3 row">
                  <label for="" class="form-label">Dirección</label>
                    <div class="input-group">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" aria-describedby="establecimiento" v-model="direccion" placeholder="Ingrese una Dirección">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 row">
                         <label for="" class="form-label">Telefóno</label>
                    <div class="input-group">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control" aria-describedby="establecimiento" v-model="telefono" placeholder="Ingrese un Teléfono"> 
                    </div>
                    </div>
                    <div class="col-md-6 row">
                         <label for="" class="form-label">NIT</label>
                        <div class="input-group">
                            <span class="input-group-text" id="establecimiento"><i class="bi bi-123"></i></span>
                            <input type="text" class="form-control" aria-describedby="establecimiento" v-model="nit" placeholder="Ingrese un NIT">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 row">
                         <label for="" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-envelope"></i></span>
                        <input type="text" class="form-control" aria-describedby="establecimiento" v-model="email" placeholder="Ingrese un e-mail"> 
                    </div>
                    </div>
                    <div class="col-md-6 row">
                         <label for="" class="form-label">Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text" id="establecimiento"><i class="bi bi-person-fill-add"></i></span>
                            <input type="text" class="form-control" aria-describedby="establecimiento" v-model="contacto" placeholder="Ingrese un Nombre">
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                  <label for="" class="form-label">Observaciones</label>
                    <div class="input-group">
                        <span class="input-group-text" id="obs"><i class="bi bi-eye"></i></span>
                        <input type="text" class="form-control" aria-describedby="obs" v-model="observ" placeholder="Ingrese una observación" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div v-if="errorProvedor" class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                    <li v-for="error in errorMostrarMsjProvedor" :key="error">{{ error }}</li>
                    </ul>
                </div>
              <div class="col-md-8 col-md-offset-3">
                <button type="button" class="btn btn-warning" @click="actualizarProvedor" v-if="estadomodal==0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                    </svg>Actualizar
                </button>
                <button type="button" class="btn btn-success" @click="saveProvedor" v-if="estadomodal==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
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
    const provedores = ref([]);
    const buscar = ref('');
    const criterio = ref('nomunidad');
    const modalpdf = ref(false);
    const pdf = ref('');
    const mostrarModal = ref(false);
    // Variables para el modal de Partidas
    const nompro = ref('');
    const direccion = ref('');
    const telefono = ref('');
    const nit = ref('');
    const email = ref('');
    const contacto = ref('');
    const observ = ref('');

    const errorProvedor = ref(0);
    const estadomodal = ref(1); // 1 para nuevo, 0 para editar
    const tituloModal = ref('');
    const idprovedor = ref(0);
    const errorMostrarMsjProvedor = ref([]);
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
    const obtenerProvedores = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/provedores?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            provedores.value = response.data.provedores.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener los Provedores:', error);
        }
    };
    const saveProvedor = async () => {
        try {
        if (validarProvedor()) {return;}
            const response = await axios.post('/provedores/registrar', {
                nompro: nompro.value,
                direccion: direccion.value,
                telefono: telefono.value,
                nit: nit.value,
                email: email.value,
                contacto: contacto.value,
                observ: observ.value
            });
                swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
                mostrarModal.value = false;
                obtenerProvedores(1, '', '');
                nompro.value = '';
                direccion.value = '';
                telefono.value = '';
                nit.value = '';
                email.value = '';
                contacto.value = '';
                observ.value = '';
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
    const actualizarProvedor = async () => {
        try {
            if (validarProvedor()) {return;}
            const response = await axios.put('/provedores/actualizar', {
                id: idprovedor.value,
                nompro: nompro.value,
                direccion: direccion.value,
                telefono: telefono.value,
                nit: nit.value,
                email: email.value,
                contacto: contacto.value,
                observ: observ.value
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
            mostrarModal.value = false;
            obtenerProvedores(1, '', '');
            nompro.value = '';
            direccion.value = '';
            telefono.value = '';
            nit.value = '';
            email.value = '';
            contacto.value = '';
            observ.value = '';
        } catch (error) {
            console.error('Error al actualizar la provedor:', error);
        }
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerProvedores(page,'','');
    };
    function addProvedor() {
        mostrarModal.value = true;
        tituloModal.value = 'Nuevo Provedor';
        idprovedor.value = 0;
        nompro.value = '';
        direccion.value = '';
        telefono.value = '';
        nit.value = '';
        email.value = '';
        contacto.value = '';
        observ.value = '';
        errorProvedor.value = 0;
        errorMostrarMsjProvedor.value = []; 
        estadomodal.value = 1; // Cambia a modo nuevo
    };
    function updateProvedor(provedor) {
        mostrarModal.value = true;
        tituloModal.value = 'Editar Unidad';
        estadomodal.value = 0; // Cambia a modo editar
        idprovedor.value = provedor.id;
        nompro.value = provedor.nompro;
        direccion.value = provedor.direccion;
        telefono.value = provedor.telefono;
        nit.value = provedor.nit;
        email.value = provedor.email;
        contacto.value = provedor.contacto;
        observ.value = provedor.observaciones;
        errorProvedor.value = 0;
        errorMostrarMsjProvedor.value = [];
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function cerrarModal() {
        mostrarModal.value = false;
        nompro.value = '';
        direccion.value = '';
        telefono.value = '';
        nit.value = '';
        email.value = '';
        contacto.value = '';
        observ.value = '';
        errorProvedor.value = 0;
        errorMostrarMsjProvedor.value = [];
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/provedores/pdf';
    };
    function validarProvedor() {
        errorProvedor.value = 0
        errorMostrarMsjProvedor.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/
        const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        const validPhone = /^[0-9]{8,15}$/
        if (
            !nompro.value ||
            !validText.test(nompro.value)
        ) {
            errorMostrarMsjProvedor.value.push("Nombre del Provedor vacío o incorrecto")
        }
        if (
            !direccion.value ||
            !validText.test(direccion.value)
        ) {
            errorMostrarMsjProvedor.value.push("Dirección vacía o incorrecta")
        }
        if (
            !telefono.value ||
            !validPhone.test(telefono.value)
        ) {
            errorMostrarMsjProvedor.value.push("Teléfono vacío o incorrecto")
        }
        if (
            !nit.value ||
            !validText.test(nit.value)
        ) {
            errorMostrarMsjProvedor.value.push("NIT vacío o incorrecto")
        }
        if (
            !email.value ||
            !validEmail.test(email.value)
        ) {
            errorMostrarMsjProvedor.value.push("E-mail vacío o incorrecto")
        }
        if (
            !contacto.value ||
            !validText.test(contacto.value)
        ) {
            errorMostrarMsjProvedor.value.push("Contacto vacío o incorrecto")
        }
        if (
            !observ.value ||
            !validText.test(observ.value)
        ) {
            errorMostrarMsjProvedor.value.push("Observación vacía o incorrecta")
        }
        if (errorMostrarMsjProvedor.value.length) {
            errorProvedor.value = 1
        }

        return errorProvedor.value
    };
    const deleteProvedor = async (id) => {
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
            const response = await axios.delete(`/provedores/eliminar/${id}`)

            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerProvedores(1, '', '');
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
    obtenerProvedores(1,'','');
    });
</script>