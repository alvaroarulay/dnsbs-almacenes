require ('./bootstrap');
window.Vue = require('vue');

import { createApp, ref, onMounted } from 'vue'
import axios from 'axios';
import Escritorio from './components/Escritorio.vue'
import User from './components/User.vue'
import NewUser from './components/Newuser.vue'
import Rol from './components/Rol.vue'
import bbdd from './components/bbdd.vue'
import almacenes from './components/almacenes.vue'
import grupos from './components/grupos.vue'
import unidades from './components/unidades.vue'; 
import provedores from './components/proveedores.vue';
import articulos from './components/articulos.vue';
import personal from './components/personal.vue';
import pedido from './components/pedidos.vue';
import compras from './components/compras.vue';
import notasentrega from './components/notaentrega.vue';
import notasalida from './components/notasalida.vue';
import reporte from './components/reportes.vue';

const app = createApp({ 
  setup() {
  return {
    menu : ref(0)
  }}
});
if (process.env.NODE_ENV === 'production') {
    app.config.devtools = false;
    app.config.productionTip = false;
  }

app.component("escritorio", Escritorio);
app.component('newuser',NewUser);
app.component("usuarios",User);
app.component("rol",Rol);
app.component("bbdd",bbdd);
app.component('almacenes',almacenes);
app.component('grupos',grupos);
app.component('unidades',unidades);
app.component('provedores',provedores);
app.component('articulos',articulos);
app.component('personal',personal);
app.component('pedido',pedido);
app.component('compras',compras);
app.component('notaentrega',notasentrega);
app.component('notasalida',notasalida);
app.component('reportes',reporte);
app.use().mount('#app')
  


