<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      
      </div>
        <div class="tile">
            <h3 class="tile-title">Notas de Entrada</h3>
            <div class="row mb-3">
                <div class="col-md-6 col-md-offset-3 justify-content-start">
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
                <div class="text-end col-md-6">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="provedores.nompro">Proveedor</option>
                            <option value="personal.nomper">Nombre Persona</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerNotas(1, buscar.toUpperCase(), criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerNotas(1, buscar.toUpperCase(), criterio)">
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
                            <th>Proveedor</th>
                            <th>Fecha de Nota</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Fecha de Creación</th>
                            <th>Personal Vinculado</th> 
                            <th>Opciones</th>  
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entrada in entradas" :key="entrada.id">
                            <td>{{ entrada.numero_anual }}</td>
                            <td>{{ entrada.anio }}</td>
                            <td>{{ entrada.nompro}}</td>
                            <td>{{ new Date(entrada.fecha).toLocaleDateString() }}</td>
                            <td style="text-align: right;">{{ format(entrada.cantidad) }}</td>
                            <td style="text-align: right;">{{ format(entrada.total) }}</td>
                            <td>{{ new Date(entrada.created_at).toLocaleDateString() }}</td>
                            <td>{{ entrada.nomper }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" @click="pdfnota(entrada)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" @click="updatenota(entrada)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="deletenota(entrada.numero_anual,entrada.anio)">
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
    
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaledit}" role="dialog" aria-labelledby="myModalLabel" style="overflow-y: scroll;" >
          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Agregar Factura</h4>
                      <button type="button" class="close btn btn-danger" @click="cerrarModal()" aria-label="Close" >
                      <span aria-hidden="true">×</span>
                      </button>
                  </div>
                    <div class="modal-body"> 
                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label for="person" class="form-label">Proveedor</label>
                            <div class="input-group">
                                <span class="input-group-text" id="person"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" aria-describedby="person" placeholder="ingrese nit" v-model="snit" @keyup.enter="InputProveedor">
                                <button class="btn btn-info btn-sm" @click="InputProveedor"> Buscar</button>
                            </div>
                            </div>
                            <div class="col-md-6">
                            <label for="" class="form-label">Nro. de NIT</label>
                            <div class="input-group">
                                <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" aria-describedby="cantidad" v-model="nit" disabled>
                            </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                             <div class="col-md-6">
                            <label for="" class="form-label">Provedor</label>
                            <div class="input-group">
                                <span class="input-group-text" id="cantidad"><i class="bi bi-people"></i></span>
                                <input type="text" class="form-control" aria-describedby="cantidad" v-model="nombreProveedor" disabled>
                            </div>
                            </div>
                            <div class="col-md-6">
                            <label for="person" class="form-label">Nro. de factura</label>
                            <div class="input-group">
                                <span class="input-group-text" id="person"><i class="bi bi-hash"></i></span>
                                <input type="number" class="form-control" aria-describedby="person" v-model="nrofac">
                            </div>
                            </div>
                           
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label for="person" class="form-label">Cod. de Control</label>
                            <div class="input-group">
                                <span class="input-group-text" id="person"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" aria-describedby="person" v-model="codcontrol">
                            </div>
                            </div>
                            <div class="col-md-6">
                            <label for="" class="form-label">Cod. Autorización</label>
                            <div class="input-group">
                                <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                                <input type="text" class="form-control" aria-describedby="cantidad" v-model="codAutorizacion">
                            </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label for="person" class="form-label">fecha</label>
                            <div class="input-group">
                                <span class="input-group-text" id="person"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" aria-describedby="person" v-model="fechafac">
                            </div>
                            </div>
                            <div class="col-md-6">
                            <label for="" class="form-label">Monto</label>
                            <div class="input-group">
                                <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                                <input type="number" class="form-control" aria-describedby="cantidad" v-model="monto">
                            </div>
                            </div>
                        </div>
                    </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="guardarCambios()">Guardar</button>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                  </div>
              </div>
        </div>
    </div>
    </main>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
    const gestiones = ref([]);
    const gestionSeleccionada = ref(0);
    const tituloAlmacen= ref('');
    const entradas = ref([]);
    const buscar = ref('');
    const criterio = ref('provedores.nompro');
    const modalpdf = ref(false);
    const pdf = ref('');
    const modaledit = ref(false);
    const snit = ref('');
    const nit = ref('');
    const nombreProveedor = ref('');
    const nrofac = ref(0);
    const codcontrol = ref('');
    const codAutorizacion = ref('');
    const fechafac = ref('');
    const monto = ref(0);
    const idprovedor = ref(0);
    const idfactura = ref(0);
    const num_anual = ref(0);
    const gestion = ref(0);
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
    const obtenerNotas = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/entradas/notas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&anio=' + gestionSeleccionada.value);
            entradas.value = response.data.entradas.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener las Entradas:', error);
        }
    };
     const format = (value) => {
    return new Intl.NumberFormat('es-BO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerNotas(page,'','');
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = `/entradas/pdf?gestion=${gestionSeleccionada.value}`;
    };
    function updatenota(data=[]){
        modaledit.value=true;
        listarFactura(data.numero_anual,data.anio);
        idprovedor.value=data.id_provedor;
        //obtenerActivos(data.numero_anual,data.anio);
    };
    const listarFactura = async (nro, anio) => {
        try {
            const response = await axios.get(`/facturas?numeroanual=${nro}&anio=${anio}`);
            if (!response.data) {
               limpiarCampos();
            }else{
                const factura = response.data;
                idfactura.value = factura.id;
                nrofac.value = factura.nro;
                codcontrol.value = factura.codcontrol;
                codAutorizacion.value = factura.codautorizacion;
                fechafac.value = factura.fecha;
                monto.value = factura.monto;
                nombreProveedor.value = factura.razon;
                idprovedor.value = factura.id_provedor;
                nit.value = factura.nit;
                num_anual.value = nro;
                gestion.value = anio;
            }
        } catch (error) {
            console.error('Error al obtener la factura:', error);
        }
    };
    function limpiarCampos(){
        idfactura.value = 0;
        nrofac.value = 0;
        codcontrol.value = '';
        codAutorizacion.value = '';
        fechafac.value = '';
        monto.value = 0;
        nombreProveedor.value = '';
        nit.value = 0;
    }
     const InputProveedor = async () => {
        try {
            if (!snit.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, ingrese un NIT de proveedor.',
                });
                return;
            }
            const response = await axios.get(`/provedores/buscar/${snit.value}`);
            if (response.data[0]) {
                const provedor = response.data[0];
                idprovedor.value=provedor.id;
                nit.value=provedor.nit;
                nombreProveedor.value=provedor.nompro;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Proveedor no encontrado',
                });
            }
        } catch (error) {
            console.error('Error al buscar el proveedor:', error);
        }
    };
     const deletenota = async (nro,anio) => {
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
                const response = await axios.delete(`/entradas/eliminar/${nro}/${anio}`)
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            obtenerNotas(1, '', '');
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
    function cerrarModal(){
        modaledit.value=false;
        limpiarCampos();
    }; 
    function onChangeGestion(e){
        gestionSeleccionada.value = e.target.value;
        if (gestionSeleccionada.value != 0) {
            obtenerNotas(1, '', '');
        } else {
            entradas.value = [];
        }
    }
    function pdfnota(data=[]) {
        modalpdf.value = true;
        pdf.value = '/entradas/entradapdf/'+ data.fecha+ '/' + data.anio + '/' + data.numero_anual;
    };
    function guardarCambios(){
        let idfactura_local = idfactura.value;
        let valores = {
            id: idfactura_local,
            nro: nrofac.value,
            fecha: fechafac.value,
            codautorizacion: codAutorizacion.value,
            codcontrol: codcontrol.value,
            monto: monto.value,
            nro_anual: num_anual.value,
            gestion: gestion.value,
            id_provedor: idprovedor.value,
        };
        if(idfactura_local){
        axios.put('/facturas/actualizar', valores)
            .then((response) => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.data.message,
                });
                cerrarModal();
            }).catch((error) => {
                console.error('Error al guardar la factura:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo guardar la factura.',
                });
                cerrarModal();
            })
        }else{
            axios.post('/facturas/registrar', valores)
            .then((response) => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.data.message,
                });
                cerrarModal();
            }).catch((error) => {
                console.error('Error al guardar la factura:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo guardar la factura.',
                });
                cerrarModal();
            })
        }
    };
    onMounted(() => {
        obtenerGestiones();
        obtenertitulo();
        obtenerNotas(1,'','');
});
</script>