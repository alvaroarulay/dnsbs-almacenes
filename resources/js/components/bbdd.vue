<template>
    <main class="app-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg mt-12">
                    <div class="card-header">
                        <h3>Respaldo de la Base de Datos SQL</h3>
                    </div>
                       <div class="card-body">
                            <div class="tile-body table-responsive table-fixed">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Archivo</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDb.length">
                                        <tr v-for="base in arrayDb" :key="base.id">
                                            <td>
                                                <button type="button" @click="restaurar(base.id)" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Editar">
                                                <i class="bi bi-check-all"></i>
                                                </button>
                                            </td>
                                            <td v-text="base.archivo"></td>
                                            <td v-text="base.usuar"></td>
                                        </tr>                                
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="3">
                                                NO hay Datos
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item" v-if="pagination.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                        </li>
                                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                        </li>
                                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <div class="card-footer">
                        <button @click="viafzip()" class="btn btn-success btn-lg">Generar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
  data() {
    return {
         arrayDb:[],
           pagination : {
          'total' : 0,
          'current_page' : 0,
          'per_page' : 0,
          'last_page' : 0,
          'from' : 0,
          'to' : 0,
      },
      offset : 3,
    }
  },
   computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
  methods: {
     Obtenerbase(page, buscar,criterio){
        let me=this;
        var url= '/backup/?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio ;
        axios.get(url).then( (response) =>{
            var respuesta= response.data;
            me.arrayDb = respuesta.base.data;
            me.pagination = respuesta.pagination;
        })
        .catch( (error) =>{
            console.log(error);
        });
    },
      cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.Obtenerbase(page,buscar,criterio);
    },
      async restaurar(id){
      try {
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esta acción!',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, restaurar!',
                cancelButtonText: 'Cancelar',
            });

            if (result.isConfirmed) {
                const response = await axios.get(`/procesar-sql/${id}`)
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: response.data.message,
            });
            }

        } catch (error) {
            console.error('Error al eliminar la provedor:', error);
            Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo Restaurar',
            });
        }
    },
    viafzip(){
        window.open('/download-sql');
    }
  },
  mounted() {
    this.Obtenerbase(1,'','');
  }
}
</script>