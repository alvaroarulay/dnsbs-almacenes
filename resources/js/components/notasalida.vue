<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      </div>
        <div class="tile">
            <h3 class="tile-title">Notas de Pedido</h3>
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                     <div class="row">
                        <div class="col-md-4">
                            <label for="form-label">Gestión</label>
                        </div>
                        <div class="col-md-4">
                            <select name="" id="" class="form-select" v-model="gestionSeleccionada" @change="onChangeGestion($event)">
                                <option v-for="gestion in gestiones" :key="gestion.gestion" :value="gestion.gestion">
                                    {{ gestion.gestion }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                             <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                        </div>
                    </div> 
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Personal:</label>
                        </div>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerNotasSalida(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerNotasSalida(1, buscar, criterio)">
                            <i class="bi bi-search"></i> Buscar </button>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table" id="tabla-provedores">
                        <thead>
                        <tr class="table-secondary">
                            <th>Nro. de Nota</th>
                            <th>Gestión</th>
                            <th>Fecha de Nota</th>
                            <th>Fecha de Creación</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Personal Vinculado</th> 
                            <th>Opciones</th>  
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="salida in salidas" :key="salida.id">
                            <td>{{ salida.numero_anual }}</td>
                            <td>{{ salida.anio }}</td>
                            <td>{{ new Date(salida.fecha).toLocaleDateString() }}</td>
                            <td style="text-align: right;">{{ format(salida.cantidad) }}</td>
                            <td style="text-align: right;">{{ format(salida.total) }}</td>
                            <td>{{ new Date(salida.created_at).toLocaleDateString() }}</td>
                            <td>{{ salida.nomper }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" @click="pdfnotasalida(salida)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deletenosalida(salida.numero_anual,salida.anio)">
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
    </main>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
    const gestiones = ref([]);
    const gestionSeleccionada = ref(0);
    const tituloAlmacen= ref('');
    const salidas = ref([]);
    const buscar = ref('');
    const criterio = ref('personal.nomper');
    const modalpdf = ref(false);
    const pdf = ref('');
    const modaledit = ref(false);
    const selectbusqueda = ref(0);
    const arrayActivos = ref([]);
    const arrayProveedores = ref([]);
    const arrayPersonales = ref([]);
  
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
    const obtenerGestiones = async () => {
        if (gestionSeleccionada.value == 0) {
            const fechaActual = new Date();
            gestionSeleccionada.value = fechaActual.getFullYear();
        }
        try {
            const response = await axios.get('/gestiones');
            gestiones.value = response.data;
            gestionSeleccionada.value = response.data[0].gestion || 0;
        } catch (error) {
            console.error('Error al obtener las gestiones:', error);
        }
    };
    const obtenertitulo = async () => {
        try {
            const response = await axios.get('/almacen/titulo');
            tituloAlmacen.value = response.data.nomalmacen || '';
        } catch (error) {
            console.error('Error al obtener el título del almacén:', error);
        }   
    };
    const obtenerNotasSalida = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/salidas/notas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio+'&anio=' + gestionSeleccionada.value);
            salidas.value = response.data.salidas.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener las Salidas:', error);
        }
    };
    const listarArticulo = async (page, buscar, criterio) => {
        try {
            const response = await axios.get(`/articulos?page=${page}&buscar=${buscar}&criterio=${criterio}`);
            arrayActivos.value = response.data.articulos.data;
            Object.assign(pagination, response.data.pagination);
        } catch (error) {
            console.error('Error al listar articulos:', error);
        }
    };
    const listarProvedor = async (page, buscar, criterio) => {
        try {
            const response = await axios.get(`/provedores?page=${page}&buscar=${buscar}&criterio=${criterio}`);
            arrayProveedores.value = response.data.provedores.data;
            Object.assign(pagination, response.data.pagination);
        } catch (error) {
            console.error('Error al listar proveedores:', error);
        }
    };
    const listarPersonal = async (page, buscar, criterio) => {
        try {
            const response = await axios.get(`/personal?page=${page}&buscar=${buscar}&criterio=${criterio}`);
            arrayPersonales.value = response.data.personal.data;
            Object.assign(pagination, response.data.pagination);
        } catch (error) {
            console.error('Error al listar personal:', error);
        }
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerNotasSalida(page,'','');
    };
    function verpdf() {
        modalpdf.value = true;
         pdf.value = `/salidas/pdf?gestion=${gestionSeleccionada.value}`;
    };
    function onChangeGestion(e){
        gestionSeleccionada.value = e.target.value;
        if (gestionSeleccionada.value != 0) {
            obtenerNotasSalida(1, '', '');
        } else {
            entradas.value = [];
        }
    }
     const deletenosalida = async (nro,anio) => {
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
                const response = await axios.delete(`/salidas/eliminar/${nro}/${anio}`)
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerNotasSalida(1, '', '');
            }

        } catch (error) {
            console.error('Error al eliminar la provedor:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo eliminar la provedor',
            });
        }
    }
    const format = (value) => {
    return new Intl.NumberFormat('es-BO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
    };
    function limpiarCampos() {
        
    };
    function pdfnotasalida(data=[]) {
        modalpdf.value = true;
        pdf.value = '/salidas/salidapdf/'+ data.fecha+ '/' + data.anio + '/' + data.numero_anual;
    };
    onMounted(() => {
        obtenerGestiones();
        obtenertitulo();
        obtenerNotasSalida(1,'','');
});
</script>