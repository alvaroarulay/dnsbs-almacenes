<template>
    <main class="app-content">
        <div class="app-title">
          <h1><i class="bi bi-speedometer"></i> Escritorio</h1>
          <p>Progreso de Inventario</p>
        </div>
       <div class="row">
          <div class="col-md-4">
            <div class="card widget-small primary coloured-icon">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-receipt" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3rem;">{{ articulos }}</h2>
                    <div class="mt-2 lead">Productos</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card widget-small info coloured-icon">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-cart" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3em;">{{salidas}}</h2>
                    <div class="mt-2 lead">Salidas</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card widget-small warning coloured-icon" >
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-truck" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3em;">{{entradas}}</h2>
                    <div class="mt-2 lead">Entradas</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
       </div>
       <div class="row">
          <div class="col-md-4">
            <div class="card widget-small warning coloured-icon">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-person" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3em;">{{personal}}</h2>
                    <div class="mt-2 lead">Personal</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card widget-small danger coloured-icon" >
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-bag" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3em;">{{provedores}}</h2>
                    <div class="mt-2 lead">Provedores</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card widget-small info coloured-icon" >
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <i class="icon bi bi-people-fill" style="font-size: 4rem;"></i>
                  </div>
                  <div class="col-md-6 text-center">
                    <h2 class="card-title" style="font-size:3em;">{{users}}</h2>
                    <div class="mt-2 lead">Usuarios</div>
                  </div>
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
    const entradas= ref(0);
    const articulos= ref(0);
    const salidas= ref(0);
    const provedores= ref(0);
    const almacenes= ref(0);
    const users= ref(0);
    const personal= ref(0);
    const obtenerDatos = async()=>{
        try {
            const response = await axios.get('/escritorio');
            entradas.value = response.data.entradas;
            salidas.value = response.data.salidas;
            articulos.value = response.data.articulos;
            provedores.value = response.data.provedores;
            users.value = response.data.users;
            personal.value=response.data.personal;
            almacenes.value = response.data.almacenes;
        } catch (error) {
            console.error('Error al obtener las Entradas:', error);
        }
    }
    
onMounted(() => {
  obtenerDatos();
});
</script>