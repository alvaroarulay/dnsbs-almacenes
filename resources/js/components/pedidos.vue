<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      
      </div>
      <template v-if="!newpedido">
        <div class="tile">
            <h3 class="tile-title">Pedidos</h3>
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addPedido"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" @click="verpdf"><i class="bi bi-file-earmark-pdf"></i>Ver Pdf</button>
                </div>
                <div class="text-end col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="inputGroupSelect01">Criterio:</label>
                        </div>
                        <select class="form-select" id="inputGroupSelect01" v-model="criterio">
                            <option selected value="descripcion">Descripción</option>
                            <option value="codigo">Código</option>
                        </select>
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerSalidas(1, buscar, criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerSalidas(1, buscar, criterio)">
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
                            <th>Descripción</th>
                            <th>Nro. de Nota</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad solicitada</th>
                            <th>fecha</th>
                            <th>Personal Vinculado</th>   
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(salida,index) in salidas" :key="index">
                            <td>{{ index+1 }}</td>
                            <td>{{ salida.codigo }}</td>
                            <td>{{ salida.descripcion }}</td>
                            <td>{{ salida.nota }}</td>
                            <td>{{ salida.precio_unitario }}</td>
                            <td>{{ salida.cantidad }}</td>
                            <td>{{ salida.fecha }}</td>
                            <td>{{ salida.personal }}</td>
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
      </template>
       <template v-else-if="newpedido">
        <div class="row">
        <div class="col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-header text-bg-primary mb-3">Articulos</div>

                <div class="card-body text-primary">
                    <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="articulos" class="form-label">Busqueda</label>
                          <div class="input-group">
                            <span class="input-group-text" id="almacen"><i class="bi bi-search"></i></span>
                            <input autofocus class="form-control" aria-describedby="almacen" placeholder="ingrese un codigo" @keyup.enter="inputArticulo(codart)" v-model="codart">
                            <button class="btn btn-info btn-sm" @click="buscarArticulo">Buscar</button>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="" class="form-label">Código</label>
                          <div class="input-group">
                            <span class="input-group-text" id="codigo"><i class="bi bi-upc"></i></span>
                            <input type="text" class="form-control" aria-describedby="codigo" v-model="codigoArticulo" disabled>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="form-label">Descripción</label>
                        <div class="input-group mb-3 ">
                            <span class="input-group-text" id="descripcion"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-alphabet-uppercase" viewBox="0 0 16 16">
                              <path d="M1.226 10.88H0l2.056-6.26h1.42l2.047 6.26h-1.29l-.48-1.61H1.707l-.48 1.61ZM2.76 5.818h-.054l-.75 2.532H3.51zm3.217 5.062V4.62h2.56c1.09 0 1.808.582 1.808 1.54 0 .762-.444 1.22-1.05 1.372v.055c.736.074 1.365.587 1.365 1.528 0 1.119-.89 1.766-2.133 1.766zM7.18 5.55v1.675h.8c.812 0 1.171-.308 1.171-.853 0-.51-.328-.822-.898-.822zm0 2.537V9.95h.903c.951 0 1.342-.312 1.342-.909 0-.591-.382-.954-1.095-.954zm5.089-.711v.775c0 1.156.49 1.803 1.347 1.803.705 0 1.163-.454 1.212-1.096H16v.12C15.942 10.173 14.95 11 13.607 11c-1.648 0-2.573-1.073-2.573-2.849v-.78c0-1.775.934-2.871 2.573-2.871 1.347 0 2.34.849 2.393 2.087v.115h-1.172c-.05-.665-.516-1.156-1.212-1.156-.849 0-1.347.67-1.347 1.83"/>
                            </svg></span>
                            <input type="text" class="form-control" aria-describedby="descripcion" v-model="descripcionArticulo" disabled>
                        </div>
                    </div>
                     <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="articulos" class="form-label">Unidad de Medida</label>
                          <div class="input-group">
                            <span class="input-group-text" id="unidad"><i class="bi bi-rulers"></i></span>
                            <input type="text" class="form-control" aria-describedby="unidad" v-model="unidad" disabled>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="" class="form-label">Stock</label>
                          <div class="input-group">
                            <span class="input-group-text" id="stock"><i class="bi bi-hash"></i></span>
                            <input type="text" class="form-control" aria-describedby="stock" v-model="stock" disabled>
                          </div>
                        </div>
                    </div>

                 
                </div>
                <div class="card-footer">
                    <div v-if="errorpedido" class="alert alert-danger">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                        <li v-for="error in Mostrarerror" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="text-center d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary" type="button" @click="agregarProducto"><i class="bi bi-plus-circle-fill me-2"></i>Agregar</button>
                        <button class="btn btn-danger" @click="cerrarNuevo"><i class="bi bi-x-circle-fill me-2"></i>Cancelar</button>
                    </div>
                 </div>
            </div>
        </div>
        <div class="col-md-6">
          <div class="card border-info mb-3">
              
                <div class="card-header text-bg-info mb-3">Pedido</div>
                <div class="card-body text-primary">
                     <div class="row mb-3">
                        <label for="" class="form-label">Cantidad</label>
                        <div class="input-group">
                        <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                        <input type="number" required class="form-control" aria-describedby="cantidad" v-model="cantidad"  >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="person" class="form-label">Personal</label>
                          <div class="input-group">
                            <span class="input-group-text" id="person"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" aria-describedby="person" placeholder="ingrese ci" v-model="codpersonal" @keyup.enter="inputPersonal">
                            <button class="btn btn-info btn-sm" @click="buscarPersonal">Buscar</button>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="" class="form-label">Carnet de Identidad</label>
                          <div class="input-group">
                            <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                            <input type="text" class="form-control" aria-describedby="cantidad" v-model="carnetIdentidad" disabled>
                          </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="" class="form-label">Fecha</label>
                            <div class="input-group">
                                <span class="input-group-text" id="fecha"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" aria-describedby="fecha" v-model="fecha">
                            </div>
                        </div>
                        <div class="col-md-6">
                           <label for="" class="form-label">Nombre</label>
                            <div class="input-group ">
                                <span class="input-group-text" id="name"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" aria-describedby="name" v-model="nombrePersonal" disabled>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div v-if="errorcfpedido" class="alert alert-danger">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                        <li v-for="error in Mostrarcfpedido" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="text-center d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-info" type="button" @click="registrarpedido" v-if="!pdffinal"><i class="bi bi-check"></i>Confirmar</button>
                        <button class="btn btn-danger" @click="generarpdf" v-if="pdffinal"><i class="bi bi-pdf me-2"></i>Ver Comprobante</button>  
                        <button class="btn btn-success" @click="finalizarpedido" v-if="pdffinal"><i class="bi bi-check-square"></i>Finalizar Pedido</button>    
                    </div>
                 </div>
            </div>
        </div>
        </div>
        <div class="row card border-warning">
            <div class="card-header text-bg-warning mb-3">Detalle</div>
            <div class="card-body text-primary">
                <div class="table-responsive">
                   <table class="table">
                        <thead class="table-secondary">
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Stock</th>
                                <th>Stock Restante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(pedido,index) in arrayPedido" :key="pedido.index">
                            <td>{{ pedido.codigo }}</td>
                            <td>{{ pedido.descripcion }}</td>
                            <td v-if="updpedido!=index">{{ pedido.cantidad }}</td>
                            <td v-else-if="updpedido==index">
                                <input class="form-control-sm" type="number" v-model.number="pedido.cantidad" v-text="pedido.cantidad" @keyup.enter="cambiarpedido(index)">
                            </td>
                            <td v-if="updpedido!=index">{{ pedido.stock }}</td>
                            <td v-else-if="updpedido==index">
                                <input class="form-control-sm" type="number" v-model.number="pedido.stock" v-text="pedido.cantidad" @keyup.enter="cambiarpedido(index)">
                            </td>
                            <td >{{ sumtotal(pedido.stock,pedido.cantidad)}}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updatepedido(index)" v-if="updpedido!=index">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="delupdpedido(index)" v-if="updpedido!=index">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-success" @click="cambiarpedido(index)" v-if="updpedido==index">
                                    <i class="bi bi-check"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="4"><strong>Camtidad:</strong></th>
                                <th colspan="2">{{ total }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </template> 
        
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
    
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalbusqueda}" role="dialog" aria-labelledby="myModalLabel" style="overflow-y: scroll;" >
          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title"> {{ tituloModalActivo }}</h4>
                      <button type="button" class="close btn btn-danger" @click="cerrarModal()" aria-label="Close" >
                      <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">  
                        <div class="input-group mb-3" v-if="selectbusqueda==0">
                            <select class="form-select col-md-3" v-model="criterio">
                                <option value="codigo">Código</option>
                                <option value="descripcion">Descripción</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        <div class="input-group mb-3" v-else-if="selectbusqueda==2">
                            <select class="form-select col-md-3" v-model="criterio">
                                <option value="ciper">Carnet</option>
                                <option value="nomper">Nombre</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                      </div>
                      <div class="table-responsive" v-if="selectbusqueda==0">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>Seleccionar</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="activo in arrayActivos" :key="activo.id">
                                        <td>
                                            <button type="button" @click="agregarActivo(activo)" class="btn btn-success btn-sm">
                                            <i class="bi bi-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="activo.codigo"></td>
                                        <td v-text="activo.descripcion"></td>
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
                                    <a class="page-link" href="#" @click.prevent="changePageBusqueda(page)">{{ page }}</a>
                                    </li>
                                </ul>
                            </nav>
                      </div>
                      <div class="table-responsive" v-else-if="selectbusqueda==2">
                          <table class="table table-bordered table-striped table-sm">
                              <thead>
                                  <tr class="table-secondary">
                                      <th>Seleccionar</th>
                                      <th>Código</th>
                                      <th>Carnet</th>
                                      <th>Nombre</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="personal in arrayPersonales" :key="personal.id">
                                      <td>
                                          <button type="button" @click="agregarPersonal(personal)" class="btn btn-success btn-sm">
                                          <i class="bi bi-check"></i>
                                          </button>
                                      </td>
                                      <td v-text="personal.codper"></td>
                                      <td v-text="personal.ciper"></td>
                                      <td v-text="personal.nomper"></td>
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
                                    <a class="page-link" href="#" @click.prevent="changePageBusqueda(page)">{{ page }}</a>
                                    </li>
                                </ul>
                            </nav>
                      </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
    const tituloAlmacen= ref('');
    const salidas = ref([]);
    const buscar = ref('');
    const criterio = ref('descripcion');
    const modalpdf = ref(false);
    const pdf = ref('');
    const newpedido = ref(false);
    const idarticulo = ref(0);
    const codigoArticulo = ref('');
    const descripcionArticulo = ref('');
    const precio = ref('');
    const stock = ref(0);
    const cantidad = ref('');
    const codpersonal = ref('');
    const carnetIdentidad = ref('');
    const nombrePersonal = ref('');
    const modalbusqueda = ref(false);
    const selectbusqueda = ref(0);
    const tituloModalActivo = ref('');
    const arrayActivos = ref([]);
    const arrayPersonales = ref([]);
    const codart = ref('');
    const unidad = ref('');
    const arrayPedido = ref([]);
    const updpedido=ref(null);
    const errorpedido = ref(0);
    const Mostrarerror = ref([]);
    const errorcfpedido = ref(0);
    const Mostrarcfpedido = ref([]);
    const total = ref(0);
    const pdffinal = ref(false);
    const anio = ref(0);
    const numeroanual = ref(0);
    const fecha = ref('');
    
    
    const idpersonal = ref(0);
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
    const obtenerSalidas = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/salidas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            salidas.value = response.data.salidas.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener las salidas:', error);
        }
    };
    function buscarArticulo() {
        criterio.value = 'codigo';
        buscar.value='';
        modalbusqueda.value = true;
        selectbusqueda.value = 0;
        tituloModalActivo.value = 'Buscar Articulo';
        listarArticulo(1, buscar.value, criterio.value);
    };
    const inputArticulo = async (codart) => {
        try {
            if (!codart) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, ingrese un código de artículo.',
                });
                return;
            }
            const response = await axios.get(`/articulos/buscar/${codart}`);
            if (response.data.articulo[0]) {
                const articulo = response.data.articulo[0];
                idarticulo.value=articulo.id;
                codigoArticulo.value = articulo.codigo;
                descripcionArticulo.value = articulo.descripcion;
                unidad.value=articulo.unidad_nombre;
                stock.value = response.data.stock;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Artículo no encontrado',
                });
            }
        } catch (error) {
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Artículo no encontrado',})
            limpiarCampos();        }
    };
    function agregarActivo(data=[]){
        inputArticulo(data.codigo);
        buscar.value='';
        modalbusqueda.value=false;
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
    function sumtotal(stock,cantidad){
        let parcial = stock-cantidad;
        return parcial;
    };
    function buscarPersonal() {
        criterio.value = 'ciper';
        buscar.value='';
        modalbusqueda.value = true;
        selectbusqueda.value = 2;
        tituloModalActivo.value = 'Buscar Personal';
        listarPersonal(1, buscar.value, criterio.value);
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
    const inputPersonal = async () => {
        try {
            if (!codpersonal.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, ingrese un carnet de Identidad.',
                });
                return;
            }
            const response = await axios.get(`/personal/buscar/${codpersonal.value}`);
            if (response.data[0]) {
                const articulo = response.data[0];
                idpersonal.value=articulo.id;
                carnetIdentidad.value = articulo.ciper;
                nombrePersonal.value = articulo.nomper;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Artículo no encontrado',
                });
            }
        } catch (error) {
            console.error('Error al buscar el artículo:', error);
        }
    };
     function changePageBusqueda(page){
        if (selectbusqueda.value==0){
            if (page === pagination.current_page) return
            listarArticulo(page,'','');
        }else{
            if(selectbusqueda.value==1){
                if (page === pagination.current_page) return
                listarProvedor(page,'','');
            }else{
                if(selectbusqueda.value==2){
                    if (page === pagination.current_page) return
                    listarPersonal(page,'','');
                }
            }
        }
    };
    function agregarPersonal(data=[]){
        idpersonal.value=data.id;
        carnetIdentidad.value=data.ciper;
        nombrePersonal.value=data.nomper;
        modalbusqueda.value=false;
    };
    function cerrarModal() {
        modalbusqueda.value = false;
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerSalidas(page,'','');
    };
    function addPedido() {
        newpedido.value = true;
        fecha.value = new Date().toISOString().split('T')[0];
    };
    function cerrarNuevo() {
        newpedido.value = false;
        limpiarCampos();
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/salidas/pdf';
    };
    function agregarProducto(){
        if(validapedido()){return};
        total.value = total.value + 1;
        arrayPedido.value.push({'idarticulo':idarticulo.value,'codigo':codigoArticulo.value,'descripcion':descripcionArticulo.value,'stock':stock.value,'cantidad':cantidad.value});
      
    };
    function updatepedido(index){
        updpedido.value=index;
    };
    function cambiarpedido(index){
      updpedido.value = null;
    }
    function delupdpedido(id){
        total.value = total.value - 1;
         arrayPedido.value.splice(id,1);
    };
    function validapedido() {
        errorpedido.value=0;
        Mostrarerror.value=[];
        const validnumber = /^[-+]?\d+(\.\d+)?$/
        if (cantidad.value==0 ||!validnumber.test(cantidad.value)) { Mostrarerror.value.push('campo cantidad vacio o invalido!')}
        if (idarticulo.value==0) {Mostrarerror.value.push('seleccione un articulo!')}
        if (cantidad.value>stock.value){Mostrarerror.value.push('Stock insuficiente!')}
        if(Mostrarerror.value.length){errorpedido.value=1}
        return errorpedido.value;
    };
    const registrarpedido = async() =>{
        if(validafinal()){return};
        try {
            const response = await axios.post('/salidas/registrar', {
                'idpersonal': idpersonal.value,
                'arrayPedido': arrayPedido.value,
            });
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            anio.value=response.data.anio;
            numeroanual.value=response.data.numero_anual;
            pdffinal.value = true;
            limpiarCampos();
        } catch (error) {
            console.error('Error al registrar la salida:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo registrar la salida',
            });
        }
    };
    function finalizarpedido() {
       limpiarCampos();
       pdffinal.value = false;
       newpedido.value = false;
       fecha.value='';
       obtenerSalidas(1,'','');
    };
    function limpiarCampos() {
        idarticulo.value = 0;
        codigoArticulo.value = '';
        descripcionArticulo.value = '';
        precio.value = '';
        stock.value = 0;
        cantidad.value = '';
        unidad.value='';
        buscar.value="";
        codpersonal.value = '';
        carnetIdentidad.value = '';
        nombrePersonal.value = '';
        arrayPedido.value = [];
        updpedido.value = null;
        errorpedido.value=0;
        Mostrarerror.value=[];
        errorcfpedido.value=0;
        Mostrarcfpedido.value=[];
        total.value = 0;
    };
    function generarpdf() {
        modalpdf.value = true;
        pdf.value = '/salidas/salidapdf/'+ fecha.value + '/' + anio.value + '/' + numeroanual.value;
    };
    function validafinal(){
        errorcfpedido.value=0;
        Mostrarcfpedido.value=[];
        if(idpersonal.value==0){Mostrarcfpedido.value.push('Seleccione una Persona')};
        if(arrayPedido.value.length==0){Mostrarcfpedido.value.push('No hay Articulos ingresados!')};
        if(Mostrarcfpedido.value.length){errorcfpedido.value=1}
        return errorcfpedido.value;
    };
    onMounted(() => {
        obtenertitulo();
        obtenerSalidas(1,'','');
});
</script>