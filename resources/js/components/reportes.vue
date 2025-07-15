<template>
<main class="app-content">
   <div class="app-title">
        <div class="row">
          <h1><i class="bi bi-geo-alt"></i> Ciudad: </h1>
         </div> 
        <ul class="app-breadcrumb breadcrumb">
          <select name="" id="" class="form-select form-select-lg mb-3" v-model="ciudadSeleccionada" @change="onChangeCiudad($event)">
            <option :value=0>Todos</option>
            <option v-for="ciudad in ciudades" :key="ciudad.id" :value="ciudad.id">
                {{ ciudad.nomciudad }}
            </option>
          </select>
        </ul>
        
      </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-secondary mb-3">
                <div class="card-header text-bg-secondary mb-3">Compras y Pedidos</div>
                <div class="card-body text-primary">
                    <div class="row">
                        <label for="" class="form-label">Establecimiento</label>
                        <div class="input-group mb-3 ">
                            <span class="input-group-text" id="establecimiento"><i class="bi bi-ticket"></i></span>
                            <select name="" aria-describedby="establecimiento" class="form-select" v-model="estabSeleccionado"  @change="onChangeEstablecimiento($event)">
                                <option value="0">Todos los Establecimientos</option>
                                <option v-for="establecimiento in establecimientos" :key="establecimiento.id" :value="establecimiento.id">
                                    {{ establecimiento.nomestab }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="form-label">Nombre de Almacen</label>
                    <div class="input-group mb-3 ">
                        <span class="input-group-text" id="almacen"><i class="bi bi-ticket"></i></span>
                         <select name="" aria-describedby="almacen" class="form-select" v-model="almacenSeleccionado"  @change="onChangeAlmacen($event)">
                            <option value="0">Todos los Almacenes</option>
                            <option v-for="almacen in almacenes" :key="almacen.id" :value="almacen.id">
                                {{ almacen.nomalmacen }}
                            </option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                        <label for="" class="form-label">Partidas</label>
                        <div class="input-group mb-3">   
                             <span class="input-group-text" id="partidas"><i class="bi bi-ticket"></i></span>
                            <select name="" id="" aria-describedby="partidas" class="form-select" v-model="partidas">
                                <option value="0">Todas las partidas</option>
                                <option v-for="partida in arrayPartidas" :key="partida.id" :value="partida.id">
                                    {{partida.codigo +'-'+ partida.nompartida }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <fieldset class="mb-3">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios1" type="radio" name="optionsRadios" value="compras" checked="" v-model="chcompraspedidos">Compras (Entradas)
                                </label>
                                </div>
                                <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios2" type="radio" name="optionsRadios" value="pedidos" v-model="chcompraspedidos">Pedidos (Salidas)
                                </label>
                                </div>
                        </fieldset>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <label for="" class="form-label">Fecha Incial</label>
                            <div class="input-group mb-3 ">
                                <span class="input-group-text" id="fechai"><i class="bi bi-calendar-date"></i></span>
                                <input type="date" class="form-control" aria-describedby="fechai" v-model="fechai">
                            </div>
                        </div>
                        <div class="col-md-6">
                             <label for="" class="form-label">Fecha Final</label>
                            <div class="input-group mb-3 ">
                                <span class="input-group-text" id="fechaf"><i class="bi bi-calendar-date"></i></span>
                                <input type="date" class="form-control" aria-describedby="fechaf" v-model="fechaf">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer text-center d-grid gap-2 d-md-flex justify-content-md-end">

                    <button class="btn btn-info me-md-2" type="button" @click="graficocompras"><i class="bi bi-bar-chart-line-fill"></i>Ver Gráfico</button>
                    <button class="btn btn-danger me-md-2" type="button"  @click="pdfcompras"><i class="bi bi-file-pdf"></i>Pdf</button>
                    <button class="btn btn-success me-md-2" type="button" @click="excelcompras" ><i class="bi bi-file-earmark-excel"></i>Excel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- modal-content -->
     <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalgraficoc}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" data-target=".bd-example-modal-lg">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Documento Preliminar</h4>
                        <button type="button" class="close btn btn-sm btn-danger" @click="cerrarModalc()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="grafico">
                         <h3 class="tile-title">Partidas por Cantidad</h3>
                        <div class="ratio ratio-16x9 d-flex d-flex justify-content-center align-items-center">
                        <Bar
                                id="bar-chart"
                                :options="chartOptions"
                                :data="chartData"
                            />
                        </div>
                    </div>
                    <div class="modal-body" v-else-if="!grafico">
                          <iframe
                            :src="pdf"
                            frameBorder="0"
                            scrolling="auto"
                            height="768"
                            width="1024"
                        ></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModalc()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
</main>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import axios from 'axios'

ChartJS.register(Title, Tooltip, Legend, ArcElement, BarElement, CategoryScale, LinearScale)

    const etiquetas = ref([]);
    const valores = ref([]);
    const grafico = ref(true);

    const ciudadSeleccionada = ref(0);
    const ciudades =ref([]);
    const establecimientos = ref([]);
    const estabSeleccionado = ref(0);
    const almacenes = ref([]);
    const almacenSeleccionado = ref(0);
    const fechai = ref('');
    const fechaf = ref('');
    const arrayPartidas = ref([]);
    const partidas = ref('0');
    const modalgraficoc = ref(false);
    const chcompraspedidos = ref('compras');
    const pdf = ref('');

    const chartData = ref({
    labels: [],
    datasets: [{ data: [] }]
    })

    const chartOptions = {
    responsive: true
    }
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
            obteneralmacenes(estabSeleccionado.value);
        } else {
            estabSeleccionado.value=0;
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
        } else {
            almacenSeleccionado.value=0;
        }
       
    } catch (error) {
        console.error('Error al obtener los activos:', error);
    }
    };
    const obtenerpartidas = async () => {
        try {
            const response = await axios.get('/partidas/todos');
            arrayPartidas.value = response.data;
        } catch (error) {
            console.error('Error al obtener los partidas:', error);
        }
    };
     function onChangeCiudad(e){
        establecimientos.value=[];
        obtenerEstablecimientos(e.target.value);
        estabSeleccionado.value=0;
    };
    function onChangeEstablecimiento(e){
        if(e.target.value!=0){
            estabSeleccionado.value=e.target.value;
            obteneralmacenes(e.target.value);
        }else{
            estabSeleccionado.value = 0;
        }
    };
     function onChangeAlmacen(e){
        if(e.target.value!=0){
            almacenSeleccionado.value=e.target.value;
        }else{
            almacenSeleccionado.value = 0;
        }
    };
    function graficocompras(){
        modalgraficoc.value=true;
        grafico.value=true;
        graficacompras();
    }
    function cerrarModalc(){
        modalgraficoc.value=false;
        etiquetas.value=[];
        valores.value=[];
        chartData.value ={labels: [],datasets: [{ data: [] }]};
        pdf.value='';
    }
    const graficacompras = async () => {
        const totales = await axios.get(`/entradas/grafica?ciudad=${ciudadSeleccionada.value}&establecimiento=${estabSeleccionado.value}&almacen=${almacenSeleccionado.value}&partida=${partidas.value}&pedidos=${chcompraspedidos.value}&fechai=${fechai.value}&fechaf=${fechaf.value}`);
        totales.data.estados.forEach(row => {
            etiquetas.value.push(row.nompartida)
            valores.value.push(row.valor)
            
        })
        chartData.value = {
        labels: etiquetas.value,
        datasets: [
        {
            label: 'Partida',
            data: valores.value,
            backgroundColor: ['#0dcaf0', '#6610f2', '#fd7e14']
        }
        ]
        }
    }
    function pdfcompras(){
        modalgraficoc.value= true;
        grafico.value=false;
        pdftotal();
    }
     const pdftotal = async () => {
         try {
           pdf.value = `/entradas/pdftotal?ciudad=${ciudadSeleccionada.value}&establecimiento=${estabSeleccionado.value}&almacen=${almacenSeleccionado.value}&partida=${partidas.value}&pedidos=${chcompraspedidos.value}&fechai=${fechai.value}&fechaf=${fechaf.value}`;
       } catch (error) {
            console.error('Error al obtener los pdf:', error);
        }
    }
    onMounted(() => {   
        obtenerCiudades();
        obtenerEstablecimientos(ciudadSeleccionada.value);
        obtenerpartidas();
    });
</script>