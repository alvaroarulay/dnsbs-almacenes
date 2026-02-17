<template>
    <main class="app-content">
        <div class="app-title">
        <div>
          <h1><i class="bi bi-laptop"></i> Almacen: {{ tituloAlmacen }}</h1>
        </div>
      
      </div>
      <template v-if="!newcompra">
        <div class="tile">
            <h3 class="tile-title">Entradas</h3>
            <div class="row mb-3">
                <div class="col-md-4 col-md-offset-3 justify-content-start">
                    <button class="btn btn-primary" @click="addCompra"><i class="bi bi-plus"></i>Nuevo</button>&nbsp;&nbsp;&nbsp;
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
                        <input type="text" class="form-control" v-model="buscar" @keyup.enter="obtenerEntradas(1, buscar.toUpperCase(), criterio)"
                               placeholder="Buscar..." aria-label="Buscar" aria-describedby="button-addon2">
                        <button class="btn btn-info btn-sm" @click="obtenerEntradas(1, buscar.toUpperCase(), criterio)">
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
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad Restante</th>
                            <th>fecha</th>
                            <th>Proveedor</th>
                            <th>Responsable de Almacen</th>   
                            <th>Ver Documento</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="entrada in entradas" :key="entrada.id">
                            <td>{{ entrada.id }}</td>
                            <td>{{ entrada.codigo }}</td>
                            <td>{{ entrada.descripcion }}</td>
                            <td>{{ entrada.cantidad }}</td>
                            <td>{{ redondeo(entrada.precio_unitario)}}</td>
                            <td> <div v-if="entrada.restante >= 100">
                                    <span class="me-1 badge badge-pill bg-success">{{entrada.restante}}</span>
                                </div>
                                <div v-else-if="entrada.restante < 100 && entrada.restante >= 10">
                                    <span class="me-1 badge badge-pill bg-warning">{{entrada.restante}}</span>
                                </div>
                                <div v-else>
                                    <span class="me-1 badge badge-pill bg-danger">{{entrada.restante}}</span>
                                </div>
                            </td>
                            <td>{{ entrada.fecha }}</td>
                            <td>{{ entrada.proveedor }}</td>
                            <td>{{ entrada.personal }}</td>
                            <td v-if="entrada.ruta!=null">
                                <a class="btn btn-danger" href="#" @click="showdocument(entrada.ruta)"><i class="bi bi-filetype-pdf"></i></a>
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
      </template>
       <template v-else-if="newcompra">
        <div class="row">
            <div class="col-md-6 tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Articulo</h3>
                    <div class="btn-group text-end">
                            <button class="btn btn-primary" href="#" @click="newarticulo()" v-if="newart==0"><i class="bi bi-plus-square fs-5"></i></button>
                            <button class="btn btn-success" href="#" @click="savearticulo()" v-if="newart==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg></button>
                            <button class="btn btn-warning" href="#" @click="editarticulo()" v-if="newart==0"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-success" href="#" @click="updatearticulo()" v-if="newart==2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg></button>
                            <button class="btn btn-danger" href="#" @click="cancelararticulo()" v-if="newart==1 || newart==2"><i class="bi bi-x-circle"></i></button>
                    </div> 
                </div>

                <div class="tile-body text-primary">
                        <label for="articulos" class="form-label" v-if="newart==0">Busqueda</label>
                            <v-select
                                :options="arrayActivo" 
                                v-model="activoseleccionado" 
                                label="descrip" 
                                @update:modelValue="inputArticulo()"
                                v-if="newart==0" 
                                >   
                            </v-select>
                            <div v-if="newart!=0">
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
                            </div>
                        
                    <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="" class="form-label">Código</label>
                          <div class="input-group">
                            <span class="input-group-text" id="codigo"><i class="bi bi-upc"></i></span>
                            <input type="text" class="form-control" aria-describedby="codigo" v-model="codigoArticulo" disabled>
                          </div>
                        </div>
                        <div class="col-md-6"  v-if="newart==0">
                            <label for="articulos" class="form-label">Unidad de Medida</label>
                          <div class="input-group">
                            <span class="input-group-text" id="unidad"><i class="bi bi-rulers"></i></span>
                            <input type="text" class="form-control" aria-describedby="unidad" v-model="unidad" disabled >
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
                            <span class="input-group-text" id="descripcion"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-alphabet-uppercase" viewBox="0 0 16 16">
                              <path d="M1.226 10.88H0l2.056-6.26h1.42l2.047 6.26h-1.29l-.48-1.61H1.707l-.48 1.61ZM2.76 5.818h-.054l-.75 2.532H3.51zm3.217 5.062V4.62h2.56c1.09 0 1.808.582 1.808 1.54 0 .762-.444 1.22-1.05 1.372v.055c.736.074 1.365.587 1.365 1.528 0 1.119-.89 1.766-2.133 1.766zM7.18 5.55v1.675h.8c.812 0 1.171-.308 1.171-.853 0-.51-.328-.822-.898-.822zm0 2.537V9.95h.903c.951 0 1.342-.312 1.342-.909 0-.591-.382-.954-1.095-.954zm5.089-.711v.775c0 1.156.49 1.803 1.347 1.803.705 0 1.163-.454 1.212-1.096H16v.12C15.942 10.173 14.95 11 13.607 11c-1.648 0-2.573-1.073-2.573-2.849v-.78c0-1.775.934-2.871 2.573-2.871 1.347 0 2.34.849 2.393 2.087v.115h-1.172c-.05-.665-.516-1.156-1.212-1.156-.849 0-1.347.67-1.347 1.83"/>
                            </svg></span>
                            <input type="text" class="form-control" aria-describedby="descripcion" v-model="descripcionArticulo" :disabled="newart==0">
                        </div>
                    </div>
                     <div class="row mb-3" v-if="newart==0">
                        <label for="" class="form-label">Documentación</label>
                        <input type="file" name="document" accept=".pdf"  class="form-control"  @change="handleDocument">
                    </div>

                    <div class="row mb-3" v-if="newart==0">
                        <div class="col-md-6">
                          <label for="articulos" class="form-label">Precio Unitario</label>
                          <div class="input-group">
                            <span class="input-group-text" id="precio"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" required class="form-control" aria-describedby="precio" v-model="precio">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="" class="form-label">Cantidad</label>
                          <div class="input-group">
                            <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                            <input type="number" required class="form-control" aria-describedby="cantidad" v-model="cantidad">
                          </div>
                        </div>
                    </div>
                </div>
                 <div v-if="errorArticulo" class="alert alert-danger">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul>
                    <li v-for="error in errorMostrarMsjArticulo" :key="error">{{ error }}</li>
                    </ul>
                </div>
                <div class="tile-footer" v-if="newart==0">

                    <div v-if="errorcompra" class="alert alert-danger">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                        <li v-for="error in Mostrarerror" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="text-center d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary" type="button" @click="agregarProducto" v-if="!pdffinal"><i class="bi bi-plus-circle-fill me-2"></i>Agregar</button>
                        <button class="btn btn-danger" @click="cerrarNuevo" v-if="!pdffinal"><i class="bi bi-x-circle-fill me-2"></i>Cancelar</button>
                    </div>
                </div>
            </div>
            <!--desde Aqui-->
        <div class="col-md-6">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Proveedor</h3>
                    <div class="btn-group text-end">
                            <button class="btn btn-primary" href="#" @click="newprovedor()" v-if="newpro==0"><i class="bi bi-plus-square fs-5"></i></button>
                            <button class="btn btn-success" href="#" @click="saveprovedor()" v-if="newpro==1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg></button>
                            <button class="btn btn-warning" href="#" @click="editarprovedor()" v-if="newpro==0"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-success" href="#" @click="updateprovedor()" v-if="newpro==2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                            </svg></button>
                            <button class="btn btn-danger" href="#" @click="cancelarprovedor()" v-if="newpro==1 || newpro==2"><i class="bi bi-x-circle"></i></button>
                    </div> 
                </div>
                <div class="tile-body">
                    <label for="articulos" class="form-label" v-if="newpro==0">Busqueda</label>
                    <v-select
                        :options="arrayProvedor" 
                        v-model="provedorSeleccionado" 
                        label="nompro" 
                        @update:modelValue="InputProveedor()"
                        v-if="newpro==0" 
                        >   
                    </v-select>
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="" class="form-label">Nro. de NIT</label>
                          <div class="input-group">
                            <span class="input-group-text" id="cantidad"><i class="bi bi-hash"></i></span>
                            <input type="text" class="form-control" aria-describedby="cantidad" v-model="nit" :disabled="newpro==0">
                          </div>
                        </div>
                        <div class="col-md-6" v-if="newpro==0">
                            <label for="" class="form-label">Fecha</label>
                            <div class="input-group">
                                <span class="input-group-text" id="fecha"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" aria-describedby="fecha" v-model="fecha">
                            </div>
                        </div>
                    </div>
                    <label for="" class="form-label">Nombre Proveedor</label>
                    <div class="input-group ">
                        <span class="input-group-text" id="name"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" aria-describedby="name" v-model="nombreProveedor" :disabled="newpro==0">
                    </div>
                    <div class="row mb-3 justify-content-md-center" v-if="newpro==0">
                        <div class="form-check col-md-6">
                            <label class="form-check-label">
                                <input class="form-check-input" id="optionsRadios1" type="radio" name="optionsRadios" :value=1 checked="" v-model="factura">Compra con Factura
                            </label>
                        </div>
                        <div class="form-check col-md-6">
                            <label class="form-check-label">
                                <input class="form-check-input" id="optionsRadios1" type="radio" name="optionsRadios" :value=0 checked="" v-model="factura">Compra sin Factura
                            </label>
                        </div>
                    </div>
                     <div v-if="factura == 1" style="margin-top: 10px;" class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="" class="form-label">Nro.</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="nro"><i class="bi bi-hash"></i></span>
                                    <input type="number" class="form-control" aria-describedby="nro" v-model="nro" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label">fecha</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="fechafac"><i class="bi bi-calendar"></i></span>
                                    <input type="date" class="form-control" aria-describedby="fechafac" v-model="fechafac">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label">Monto</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="monto"><i class="bi bi-hash"></i></span>
                                    <input type="number" class="form-control" aria-describedby="monto" v-model="monto">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                 <label for="" class="form-label">Código de Control</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="codcontrol"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control" aria-describedby="codcontrol" v-model="codcontrol">
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <label for="" class="form-label">Código de Autorización</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="autorizacion"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control" aria-describedby="autorizacion" v-model="autorizacion">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div v-if="errorProvedor" class="alert alert-danger">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                        <li v-for="error in errorMostrarMsjProvedor" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                     <div v-if="errorcfcompra" class="alert alert-danger">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul>
                        <li v-for="error in Mostrarcfcompra" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                     <div class="text-center d-grid gap-2 d-md-flex justify-content-md-end" v-if="newpro==0">
                        <button class="btn btn-info" type="button" @click="registrarentrada" v-if="!pdffinal"><i class="bi bi-check"></i>Confirmar</button>
                        <button class="btn btn-danger" @click="generarpdf" v-if="pdffinal"><i class="bi bi-pdf me-2"></i>Ver Comprobante</button>  
                        <button class="btn btn-success" @click="finalizarcompra" v-if="pdffinal"><i class="bi bi-check-square"></i>Finalizar Compra</button>    
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
                                <th>Precio Unitario</th>
                                <th>Precio Final</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(compra,index) in arrayCompras" :key="compra.index">
                            <td>{{ compra.codigo }}</td>
                            <td>{{ compra.descripcion }}</td>
                            <td v-if="updcompra!=index">{{ compra.cantidad }}</td>
                            <td v-else-if="updcompra==index">
                                <input class="form-control-sm" type="number" v-model.number="compra.cantidad" v-text="compra.cantidad" @keyup.enter="cambiarcompra(index)">
                            </td>
                            <td v-if="updcompra!=index">{{ compra.precio.toFixed(2) }}</td>
                            <td v-else-if="updcompra==index">
                                <input class="form-control-sm" type="number" v-model.number="compra.precio" v-text="compra.cantidad" @keyup.enter="cambiarcompra(index)">
                            </td>
                            <td >{{ sumtotal(compra.precio,compra.cantidad)}}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" @click="updatecompra(index)" v-if="updcompra!=index">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" @click="delupdcompra(index)" v-if="updcompra!=index">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-success" @click="cambiarcompra(index)" v-if="updcompra==index">
                                    <i class="bi bi-check"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="4"><strong>Totales:</strong></th>
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
 
    </main>
</template>
<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';
    const tituloAlmacen= ref('');
    const entradas = ref([]);
    const buscar = ref('');
    const criterio = ref('descripcion');
    const modalpdf = ref(false);
    const pdf = ref('');
    const newcompra = ref(false);
    const idarticulo = ref(0);
    const codigoArticulo = ref('');
    const descripcionArticulo = ref('');
    const precio = ref('');
    const stock = ref(0);
    const cantidad = ref('');
    const idprovedor = ref(0);
    const nit = ref('');
    const nombreProveedor = ref('');
    const codpersonal = ref('');
    const carnetIdentidad = ref('');
    const nombrePersonal = ref('');
    const unidad = ref('');
    const arrayCompras = ref([]);
    const updcompra=ref(null);
    const errorcompra = ref(0);
    const Mostrarerror = ref([]);
    const errorcfcompra = ref(0);
    const Mostrarcfcompra = ref([]);
    const total = ref(0);
    const pdffinal = ref(false);
    const anio = ref(0);
    const numeroanual = ref(0);
    const fecha = ref(new Date().toISOString().slice(0, 10));
    const factura = ref(0);
    const nro = ref(0);
    const fechafac = ref(new Date().toISOString().slice(0, 10));
    const monto = ref(0);
    const codcontrol = ref('');
    const autorizacion = ref('');
    const cargando= ref(false);
    const archivo=ref(null);
    const arrayActivo = ref([]);
    const activoseleccionado = ref({id:0,descrip:'--Seleccione--'});
    const newart=ref(0);
    const partidas = ref([]);
    const unidades = ref([]);
    const partidaSeleccionada = ref(0);
    const unidadseleccionada = ref(0);
    const nompartida = ref('');
    const nomunidad = ref('');
    const fechaExpiracion = ref('');
    const errorArticulo = ref(0);
    const errorMostrarMsjArticulo = ref([]);
    const id_articulo =ref(0);
    const newpro = ref(0);
    const arrayProvedor = ref([]);
    const provedorSeleccionado = ref({id:0,nompro:'--Seleccione--'});
    const errorProvedor = ref(0);
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
    const obtenertitulo = async () => {
        try {
            const response = await axios.get('/almacen/titulo');
            tituloAlmacen.value = response.data.nomalmacen || '';
        } catch (error) {
            console.error('Error al obtener el título del almacén:', error);
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
        if(newart.value==1){
            cantidadpartida(event.target.value);
            obtenerUnidades(partidaSeleccionada.value);
        }
        if(newart.value==2){
            unidadseleccionada.value = 0;
            cantidadpartida(event.target.value);
        }
    };  
     function cantidadpartida(id){

        var url= '/articulos/partidas/'+id;

        axios.get(url).then((response)=>{
            codigoArticulo.value = response.data.partidas;
        })
        .catch(function (error) {
            console.log(error);
        });
    }
    const obtenerUnidades = async (id,id_unidad) => {
        try {
            const response = await axios.get('/unidades/todos/'+id);
            unidades.value = response.data;
            if(id_unidad){
                unidadseleccionada.value=id_unidad;
            }
        } catch (error) {
            console.error('Error al obtener las unidades:', error);
        }
    };
    const onChangeUnidad = (event) => {
        if (unidadseleccionada.value != 0) {
            nomunidad.value = unidades.value.find(unidad => unidad.id == event.target.value).nomunidad;
        } else {
            nomunidad.value = {id:0,nomunidad:'--Seleccione--'};
        }
    };
    const obtenerEntradas = async (page,buscar,criterio) => {
        try {
            const response = await axios.get('/entradas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio);
            entradas.value = response.data.entradas.data;
            Object.assign(pagination, response.data.pagination)
        } catch (error) {
            console.error('Error al obtener las Entradas:', error);
        }
    };
    function inputArticulo (){
       idarticulo.value=activoseleccionado.value.id;
       codigoArticulo.value=activoseleccionado.value.codigo;
       descripcionArticulo.value=activoseleccionado.value.descripcion;
       unidad.value=activoseleccionado.value.unidad_nombre;
    };
    const listarArticulo = async (id) => {
        try {
            const response = await axios.get(`/articulos/stock_entradas`);
            arrayActivo.value = response.data.articulos;
            if(id){
                activoseleccionado.value=arrayActivo.value.find(art=>art.id==id);
                inputArticulo();
            }
        } catch (error) {
            console.error('Error al listar articulos:', error);
        }
    };
    function sumtotal(precio,cantidad){
        let parcial = (precio*cantidad).toFixed(2);
        total.value = arrayCompras.value.reduce((acumulador, item) => acumulador + (item.cantidad*item.precio), 0).toFixed(2);
        return parcial;
    };
    const listarProvedor = async (id) => {
        try {
            const response = await axios.get(`/provedores/todos`);
            arrayProvedor.value = response.data;
            if(id){
                provedorSeleccionado.value=arrayProvedor.value.find(provedor=>provedor.id==id);
                InputProveedor();
            }
        } catch (error) {
            console.error('Error al listar proveedores:', error);
        }
    };
    function InputProveedor(){
        nit.value = provedorSeleccionado.value.nit;
        nombreProveedor.value=provedorSeleccionado.value.nompro;
        idprovedor.value = provedorSeleccionado.value.id;
    };
    function cerrarModalpdf() {
        modalpdf.value = false;
        pdf.value = '';
    };
    function changePage(page) {
        if (page === pagination.current_page) return
        obtenerEntradas(page,'','');
    };
    function addCompra() {
        newcompra.value = true;
        listarArticulo();
        listarProvedor();
    };
    function cerrarNuevo() {
        newcompra.value = false;
        limpiarCampos();
    };
    function verpdf() {
        modalpdf.value = true;
        pdf.value = '/entradas/pdf';
    };
    function agregarProducto(){
        if(validacompra()){return};
        // Objeto que quieres agregar
        const nuevoArticulo = {
        idarticulo: idarticulo.value,
        codigo: codigoArticulo.value,
        descripcion: descripcionArticulo.value,
        precio: precio.value,
        cantidad: cantidad.value
        };

        // Validación: buscar si ya existe en el array
        const existe = arrayCompras.value.some(item =>
        item.descripcion === nuevoArticulo.descripcion 
        );

        if (existe) {
        swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'Este artículo ya está en la lista.',
        }); 
        } else {
        arrayCompras.value.push(nuevoArticulo);
        }
    };
    function updatecompra(index){
        updcompra.value=index;
    };
    function cambiarcompra(index){
      updcompra.value = null;
    }
    function delupdcompra(id){
         arrayCompras.value.splice(id,1);
    };
    function validacompra() {
        errorcompra.value=0;
        Mostrarerror.value=[];
        const validnumber = /^[-+]?\d+(\.\d+)?$/
        if (precio.value==0 ||!validnumber.test(precio.value)) { Mostrarerror.value.push('campo precio vacio o invalido!')}
        if (cantidad.value==0 ||!validnumber.test(cantidad.value)) { Mostrarerror.value.push('campo cantidad vacio o invalido!')}
        if (idarticulo.value==0) {Mostrarerror.value.push('seleccione un articulo!')}
        if(Mostrarerror.value.length){errorcompra.value=1}
        return errorcompra.value;
    };
    const registrarentrada = async() =>{
        if(validafinal()){return};
        try {
            const response = await axios.post('/entradas/registrar', {
                'idprovedor': idprovedor.value,
                'arrayCompras': arrayCompras.value,
                'nro': nro.value,
                'fechafac':fechafac.value,
                'monto':monto.value,
                'codcontrol':codcontrol.value.toUpperCase(),
                'autorizacion':autorizacion.value.toUpperCase(),
                'factura':factura.value,
            });
            enviarDocumento(response.data.id);
            anio.value=response.data.anio;
            numeroanual.value=response.data.numero_anual;
            
            pdffinal.value = true;
            limpiarCampos();
        } catch (error) {
            console.error('Error al registrar la entrada:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo registrar la entrada',
            });
        }
    };
    function finalizarcompra() {
       limpiarCampos();
       pdffinal.value = false;
       newcompra.value = false;
       fecha.value='';
       obtenerEntradas(1,'','');
    };
    function limpiarCampos() {
        idarticulo.value = 0;
        codigoArticulo.value = '';
        descripcionArticulo.value = '';
        precio.value = '';
        stock.value = 0;
        cantidad.value = '';
        idprovedor.value = 0;
        nit.value = '';
        nombreProveedor.value = '';
        codpersonal.value = '';
        carnetIdentidad.value = '';
        nombrePersonal.value = '';
        arrayCompras.value = [];
        updcompra.value = null;
        errorcompra.value=0;
        Mostrarerror.value=[];
        errorcfcompra.value=0;
        Mostrarcfcompra.value=[];
        total.value = 0;
        nro.value=0;
        monto.value=0;
        fechafac.value='';
        codcontrol.value='';
        autorizacion.value='';
        factura.value=0;
        arrayActivo.value=[]
        activoseleccionado.value={id:0,descrip:'--Seleccione--'};
        arrayProvedor.value=[];
        provedorSeleccionado.value={id:0,nompro:'--Seleccione--'};
        archivo.value = null;
    };
    function generarpdf() {
        modalpdf.value = true;
        pdf.value = '/entradas/entradapdf/'+ fecha.value + '/' + anio.value + '/' + numeroanual.value;
    };
    function handleDocument(event) {
        cargando.value = false;
        const file = event.target.files[0];
        if (file) {
            const nombre = file.name.toLowerCase();
            const extensionValida = nombre.endsWith('.pdf');

            if (extensionValida) {
            archivo.value = file;
            } else {
            archivo.value = null;
            swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permiten archivos con extensión .pdf',
            });
            }
        }
    };
    async function enviarDocumento(id) {
        if (!archivo.value) {
            swal.fire({
                icon: 'info',
                title: 'Datos Guardados Correctamente',
                text: 'No se Envio ningun archivo PDF',
            });
            return;
        }
        const formData = new FormData();
        formData.append('documento', archivo.value);
        formData.append('producto_id', id);

        try {
            const response = await axios.post('/documentos/guardar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            swal.fire({
                icon: 'success',
                title: 'Guardado',
                text: 'Documento y Datos guardados correctamente',
            });

        } catch (error) {
            swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo guardar el documento',
            });
        }
    };
    function validafinal(){
        errorcfcompra.value=0;
        Mostrarcfcompra.value=[];
        if(idprovedor.value==0){Mostrarcfcompra.value.push('Seleccione un Proveedor')};
        if(arrayCompras.value.length==0){Mostrarcfcompra.value.push('No hay Productos ingresados!')};
        if(factura.value==1){
            if(nro.value==0){Mostrarcfcompra.value.push('ingrese nro. de factura!')};
            if(monto.value==0){Mostrarcfcompra.value.push('ingrese un monto')};
            if(fechafac.value==''){Mostrarcfcompra.value.push('fecha invalida!')};
            if(autorizacion.value==''){Mostrarcfcompra.value.push('nro. de autorizacion invalido!')}
        }
        if(Mostrarcfcompra.value.length){errorcfcompra.value=1}
        return errorcfcompra.value;
    };
    function redondeo(valor) {
        return parseFloat(valor).toFixed(2);
    };
    function newarticulo(){
        newart.value = 1;
        arrayActivo.value=[];
        obtenerPartidas();
        partidaSeleccionada.value=0;
        unidadseleccionada.value=0;
        codigoArticulo.value='';
        descripcionArticulo.value='';
    };
    function editarticulo(){
        if(activoseleccionado.value.id==0){
            swal.fire('Seleccione un Articulo','','error');
            return;
        }
        newart.value = 2;
        arrayActivo.value=[];
        obtenerPartidas();
        obtenerUnidades(activoseleccionado.value.id_partida,activoseleccionado.value.id_unidad);
        partidaSeleccionada.value = activoseleccionado.value.id_partida;
        id_articulo.value = activoseleccionado.value.id;
        codigoArticulo.value=activoseleccionado.value.codigo;
        descripcionArticulo.value=activoseleccionado.value.descripcion;
    };
    const savearticulo = async () => {
        try {
        if (validarArticulo()) {return;}
            const response = await axios.post('/articulos/registrar', {
                codigo: codigoArticulo.value,
                descripcion: descripcionArticulo.value.toUpperCase(),
                fecha_expiracion: fechaExpiracion.value,
                partida_id: partidaSeleccionada.value,
                unidad_id: unidadseleccionada.value
            });
            swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
                listarArticulo(response.data.id);
                
                newart.value = 0;
        } catch (error) {
            console.log(error);
            swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.data.message,
            });
        }
    };
    const updatearticulo = async () => {
        try {
            if (validarArticulo()) {return;}
            const response = await axios.put('/articulos/actualizar', {
                id: id_articulo.value,
                codigo: codigoArticulo.value,
                descripcion: descripcionArticulo.value.toUpperCase(),
                fecha_expiracion: fechaExpiracion.value,
                partida_id: partidaSeleccionada.value,
                unidad_id: unidadseleccionada.value
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
                listarArticulo(response.data.id);
                newart.value = 0;
        } catch (error) {
            console.error('Error al actualizar la Articulo:', error);
        }
    };
    function validarArticulo() {
        errorArticulo.value = 0
        errorMostrarMsjArticulo.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-/]+$/

        if (
            !descripcionArticulo.value ||
            !validText.test(descripcionArticulo.value)
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
    function cancelararticulo(){
        newart.value = 0;
        codigoArticulo.value ='';
        descripcionArticulo.value = '';
        unidad.value = '';
        precio.value = 0;
        cantidad.value = 0;
        activoseleccionado.value = {id:0,descrip:'--Seleccione--'};
        partidaSeleccionada.value=0;
        unidadseleccionada.value=0;
        errorArticulo.value='';
        errorMostrarMsjArticulo.value=[];
        listarArticulo();
    };
    function newprovedor(){
        newpro.value = 1;
        arrayProvedor.value=[];
        nit.value='';
        nombreProveedor.value='';
    };
    function editarprovedor(){
        if(provedorSeleccionado.value.id==0){
            swal.fire('Seleccione un Proveedor','','error');
            return;
        }
        newpro.value = 2;
        arrayProvedor.value=[];
        idprovedor.value = provedorSeleccionado.value.id;
        nit.value=provedorSeleccionado.value.nit;
        nombreProveedor.value=provedorSeleccionado.value.nompro;
    };
     const saveprovedor = async () => {
        try {
        if (validarProvedor()) {return;}
            const response = await axios.post('/provedores/registrar', {
                nompro: nombreProveedor.value.toUpperCase(),
                nit: nit.value,
            });
                swal.fire({
                    icon: 'success',
                    title: 'Exito!',
                    text: response.data.message,
                });
            newpro.value=0;
            listarProvedor(response.data.id);
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
    const updateprovedor = async () => {
        try {
            if (validarProvedor()) {return;}
            const response = await axios.put('/provedores/actualizar', {
                id: idprovedor.value,
                nompro: nombreProveedor.value.toUpperCase(),
                nit: nit.value,
            });
            swal.fire({
                icon: 'success',
                title: 'Exito!',
                text: response.data.message,
            });
            newpro.value=0;
           listarProvedor(response.data.id);
        } catch (error) {
            console.error('Error al actualizar la provedor:', error);
        }
    };
    function cancelarprovedor(){
        newpro.value = 0;
        nit.value ='';
        nombreProveedor.value = '';
        codcontrol.value = '';
        autorizacion.value = 0;
        provedorSeleccionado.value = {id:0,nompro:'--Seleccione--'};
        errorProvedor.value='';
        errorMostrarMsjProvedor.value=[];
        listarProvedor();
    };
    function validarProvedor() {
        errorProvedor.value = 0
        errorMostrarMsjProvedor.value = []
        const validText = /^[ a-zA-ZñÑáéíóúÁÉÍÓÚ0-9.-]+$/
        if (
            !nombreProveedor.value ||
            !validText.test(nombreProveedor.value)
        ) {
            errorMostrarMsjProvedor.value.push("Nombre del Provedor vacío o incorrecto")
        }
        if (
            !nit.value ||
            !validText.test(nit.value)
        ) {
            errorMostrarMsjProvedor.value.push("NIT vacío o incorrecto")
        }
        if (errorMostrarMsjProvedor.value.length) {
            errorProvedor.value = 1
        }

        return errorProvedor.value
    };
    function showdocument(ruta){
        window.open('/storage/' + ruta, '_blank');
    }
    onMounted(() => {
        obtenertitulo();
        obtenerEntradas(1,'','');
});
</script>