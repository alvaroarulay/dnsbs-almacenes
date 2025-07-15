<template>
<main class="app-content">
    <div class="app-title">
        <div class="div">
          <h1><i class="bi bi-geo-alt"></i> Ciudad: </h1>
         </div> 
        <ul class="app-breadcrumb breadcrumb">
          <select name="" id="" class="form-select form-select-lg mb-3" v-model="ciudadSeleccionada" @change="onChangeCiudad($event)">
            <option v-for="ciudad in ciudades" :key="ciudad.id" :value="ciudad.id">
                {{ ciudad.nomciudad }}
            </option>
          </select>
        </ul>
        
      </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card border-primary mb-3">
                <div class="card-header text-bg-primary mb-3">Establecimientos</div>

                <div class="card-body text-primary">
                    <label for="" class="form-label">Establecimiento</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-ticket"></i></span>
                        <select name="" aria-describedby="establecimiento" class="form-select" v-model="estabSeleccionado"  @change="onChangeEstablecimiento($event)">
                            <option value="0">No se encontro establecimientos</option>
                            <option v-for="establecimiento in establecimientos" :key="establecimiento.id" :value="establecimiento.id">
                                {{ establecimiento.nomestab }}
                            </option>
                        </select>
                    </div>
                    <label for="" class="form-label">Nombre de Establecimiento</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="establecimiento"><i class="bi bi-folder-plus"></i></span>
                        <input type="text" class="form-control" aria-describedby="establecimiento" v-model="nomestab" :disabled="estabenable">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <label for="" class="form-label">Dirección</label>
                            <div class="input-group mb-3 ">
                                <span class="input-group-text" id="direccion"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" class="form-control" aria-describedby="direccion" v-model="direccion" :disabled="estabenable">
                            </div>
                        </div>
                        <div class="col-md-6">
                             <label for="" class="form-label">Teléfono</label>
                            <div class="input-group mb-3 ">
                                <span class="input-group-text" id="telefono"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control" aria-describedby="telefono" v-model="telefono" :disabled="estabenable">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center d-grid gap-2 d-md-flex justify-content-md-end">

                    <button class="btn btn-primary me-md-2" type="button" v-if="newestablecimiento==0" @click="abrir"><i class="bi bi-plus-square"></i>Nuevo</button>
                    <button class="btn btn-success me-md-2" type="button" v-else-if="newestablecimiento==1" @click="savestablecimiento"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
  <path d="M11 2H9v3h2z"/>
  <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
</svg>Guardar</button>
                    <button class="btn btn-warning me-md-2" type="button" v-if="editestablecimiento==0" @click="edit"><i class="bi bi-pencil-square"></i>Editar</button>
                    <button class="btn btn-success me-md-2" type="button" v-else-if="editestablecimiento==1" @click="updatestablecimiento"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
  <path d="M11 2H9v3h2z"/>
  <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
</svg>Actualizar</button>
                    <button class="btn btn-danger" type="button" @click="cancelar" v-if="newestablecimiento==1 || editestablecimiento==1"><i class="bi bi-x-square"></i>Cancelar</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">  
            <div class="card border-info mb-3">
                <div class="card-header text-bg-info mb-3">Almacenes</div>
                <div class="card-body text-primary">
                    <label for="" class="form-label">Nombre de Almacen</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="almacen"><i class="bi bi-ticket"></i></span>
                         <select name="" aria-describedby="almacen" class="form-select" v-model="almacenSeleccionado"  @change="onChangeAlmacen($event)">
                            <option value="0">No se encontro almacenes</option>
                            <option v-for="almacen in almacenes" :key="almacen.id" :value="almacen.id">
                                {{ almacen.nomalmacen }}
                            </option>
                        </select>
                    </div>
                    <label for="" class="form-label">Nombre de Almacen</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="almacen"><i class="bi bi-folder-plus"></i></span>
                        <input type="text" class="form-control" aria-describedby="almacen" v-model="nomalmacen" :disabled="almacenenable">
                    </div>
                    <label for="" class="form-label">Ubicación Especifica</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="ubiespc"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" aria-describedby="ubiespc" v-model="ubiespec" :disabled="almacenenable">
                    </div>
                </div>
                   <div class="card-footer text-center d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-info me-md-2" type="button" @click="seleccionarAlmacen"><i class="bi bi-check2-square" ></i>Seleccionar</button>
                    <button class="btn btn-primary me-md-2" type="button" v-if="newalmacen==0" @click="abrirAlmacen"><i class="bi bi-plus-square"></i>Nuevo</button>
                    <button class="btn btn-success me-md-2" type="button" v-else-if="newalmacen==1" @click="savealmacen"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Guardar
                    </button>
                    <button class="btn btn-warning me-md-2" type="button" v-if="editalmacen==0" @click="editaralmacen"><i class="bi bi-pencil-square"></i>Editar</button>
                    <button class="btn btn-success me-md-2" type="button" v-else-if="editalmacen==1" @click="updatealmacen"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                        <path d="M11 2H9v3h2z"/>
                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>Actualizar
                    </button>
                    <button class="btn btn-danger" type="button" @click="cancelaralmacen" v-if="newalmacen==1 || editalmacen==1"><i class="bi bi-x-square"></i>Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    
</main>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
    const ciudades = ref([]);
    const ciudadSeleccionada = ref(1);
    const establecimientos = ref([]);
    const estabSeleccionado = ref(0);
    const almacenes = ref([]);
    const almacenSeleccionado = ref(0);
    const estabenable = ref(true);
    const almacenenable = ref(true);
    const newestablecimiento = ref(0);
    const editestablecimiento = ref(0);
    const newalmacen = ref(0);
    const editalmacen = ref(0);
    const direccion = ref('');
    const telefono = ref('');
    const nomestab = ref('');
    const nomalmacen = ref('');
    const ubiespec = ref(''); 

    const obtenerCiudades = async () => {
        try {
            const response = await axios.get('/ciudades');
            ciudades.value = response.data;
        } catch (error) {
            console.error('Error al obtener los activos:', error);
        }
    };
    const obtenerEstablecimientos = async (id) => {
        try {
        const response = await axios.get('/establecimientos/'+id);
        if (response.data && response.data.length > 0) {
            establecimientos.value = response.data;
            estabSeleccionado.value = establecimientos.value[0].id;
            nomestab.value= establecimientos.value[0].nomestab;
            direccion.value = establecimientos.value[0].direccion;
            telefono.value = establecimientos.value[0].telefono;
            obteneralmacenes(estabSeleccionado.value);
        } else {
            estabSeleccionado.value=0;
            nomestab.value= '';
            direccion.value = '';
            telefono.value = '';
        }
       
    } catch (error) {
        console.error('Error al obtener los activos:', error);
    }
    };
    const obteneralmacenes = async (id) => {
        try {
        const response = await axios.get('/almacen/'+id);
        if (response.data && response.data.length > 0) {
            almacenes.value = response.data;
            almacenSeleccionado.value = almacenes.value[0].id;
            nomalmacen.value= almacenes.value[0].nomalmacen;
            ubiespec.value = almacenes.value[0].ubi_espec;
        } else {
            almacenSeleccionado.value=0;
            nomalmacen.value= '';
            ubiespec.value = '';
        }
       
    } catch (error) {
        console.error('Error al obtener los activos:', error);
    }
    };
    function abrir(){
        newestablecimiento.value=1;
        editestablecimiento.value=3;
        estabenable.value=false;
        nomestab.value='';
        direccion.value = '';
        telefono.value = '';
    };
    function abrirAlmacen(){
        newalmacen.value = 1;
        editalmacen.value=3;
        almacenenable.value=false;
        nomalmacen.value='';
        ubiespec.value='';
    };
    function edit(){
        newestablecimiento.value=3;
        editestablecimiento.value=1;
        estabenable.value=false;
        obtenerEstablecimientos(ciudadSeleccionada.value);
    };
    function editaralmacen(){
        newalmacen.value=3;
        editalmacen.value=1;
        almacenenable.value=false;
    };
    function cancelar(){
        newestablecimiento.value=0;
        editestablecimiento.value=0;
        estabenable.value=true;
    };
    function cancelaralmacen(){
        newalmacen.value=0;
        editalmacen.value=0;
        almacenenable.value=true;
    };
    function onChangeCiudad(e){
        establecimientos.value=[];
        obtenerEstablecimientos(e.target.value);
        
    };
    function onChangeEstablecimiento(e){
        if(e.target.value!=0){
            estabSeleccionado.value=e.target.value;
            const res = this.establecimientos.find((estab) => estab.id == estabSeleccionado.value);
            nomestab.value = res.nomestab;
            direccion.value = res.direccion;
            telefono.value = res.telefono;
            obteneralmacenes(e.target.value);
        }else{
            estabSeleccionado.value = 0;
            nomestab.value = '';
            direccion.value = '';
            telefono.value = '';
        }
    };
     function onChangeAlmacen(e){
        if(e.target.value!=0){
            almacenSeleccionado.value=e.target.value;
            const res = this.almacenes.find((almacen) => almacen.id == almacenSeleccionado.value);
            nomalmacen.value = res.nomalmacen;
            ubiespec.value = res.ubi_espec;
        }else{
            almacenSeleccionado.value = 0;
            nomalmacen.value = '';
            ubiespec.value = '';
        }
    };
    const seleccionarAlmacen = async()=>{
        try{
            const response = await axios.get('/almacen/seleccionar/'+almacenSeleccionado.value);
            swal.fire(response.data.message,'','success');
        }catch(error){
            console.log(error)
        }
    };
    const savestablecimiento = async () =>{
        try {
            const response = await axios.post('/establecimiento/registrar', {
                'id_ciudad' : ciudadSeleccionada.value,
                'direccion' : direccion.value,
                'telefono' : telefono.value,
                'nomestab' : nomestab.value
            });
            swal.fire(response.data.message,'','success');
            obtenerEstablecimientos(ciudadSeleccionada.value);
            cancelar();
        } catch (error) {
            if (error.response) {
                // Error del servidor con código HTTP
                console.error('Respuesta del servidor:', error.response.data);
            } else if (error.request) {
                // No hubo respuesta del servidor
                console.error('Sin respuesta del servidor:', error.request);
            } else {
                // Otro error
                console.error('Error general:', error.message);
            }
        }
    };
    const savealmacen = async () =>{
        try {
            const response = await axios.post('/almacen/registrar', {
                'id_establecimiento' : estabSeleccionado.value,
                'nomalmacen' : nomalmacen.value,
                'ubiespec' : ubiespec.value
            });
            swal.fire(response.data.message,'','success');
            obteneralmacenes(almacenSeleccionado.value);
            cancelaralmacen();
        } catch (error) {
            if (error.response) {
                console.error('Respuesta del servidor:', error.response.data);
            } else if (error.request) {
                console.error('Sin respuesta del servidor:', error.request);
            } else {
                console.error('Error general:', error.message);
            }
        }
    };
    const updatestablecimiento = async () => {
        try {
            const response = await axios.put('/establecimiento/actualizar', {
                'id': estabSeleccionado.value,
                'id_ciudad' : ciudadSeleccionada.value,
                'direccion' : direccion.value,
                'telefono' : telefono.value,
                'nomestab' : nomestab.value
            });
            swal.fire(response.data.message,'','success');
            obtenerEstablecimientos(ciudadSeleccionada.value);
            cancelar();
        } catch (error) {
            if (error.response) {
                // Error del servidor con código HTTP
                console.error('Respuesta del servidor:', error.response.data);
            } else if (error.request) {
                // No hubo respuesta del servidor
                console.error('Sin respuesta del servidor:', error.request);
            } else {
                // Otro error
                console.error('Error general:', error.message);
            }
        }
    };
    const updatealmacen = async () => {
        try {
            const response = await axios.put('/almacen/actualizar', {
                'id': estabSeleccionado.value,
                'id_establecimiento' : estabSeleccionado.value,
                'nomalmacen' : nomalmacen.value,
                'ubiespec' : ubiespec.value
            });
            swal.fire(response.data.message,'','success');
            obteneralmacenes(almacenSeleccionado.value);
            cancelaralmacen();
        } catch (error) {
            if (error.response) {
                // Error del servidor con código HTTP
                console.error('Respuesta del servidor:', error.response.data);
            } else if (error.request) {
                // No hubo respuesta del servidor
                console.error('Sin respuesta del servidor:', error.request);
            } else {
                // Otro error
                console.error('Error general:', error.message);
            }
        }
    }
    onMounted(() => {
    obtenerCiudades();
    obtenerEstablecimientos(ciudadSeleccionada.value);
    obteneralmacenes(almacenSeleccionado.value);
    });
</script>