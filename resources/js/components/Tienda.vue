<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">SISTEMA DE ENVIOS</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Listado de Tiendas</h2><br/>
                    <button class="btn btn-primary btn-lg" type="button" @click="abrirModal('tienda','registrar');">
                        <i class="fa fa-plus fa-1x"></i>&nbsp;&nbsp;Agregar Tienda
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Tienda</option>
                                    <option value="sitio_web">Sitio Web</option>
                                    <option value="telefono">Telefono</option>
                                    <option value="pais">Pais</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" @keyup.enter="listarTienda(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar Tienda">
                                <button type="submit" @click="listarTienda(1,buscar,criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr class="bg-primary">
                                <th>Tienda</th>
                                <th>Sitio Web</th>
                                <th>Telefono</th>
                                <th>Pais</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Cambiar Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="tienda in arrayTienda" :key="tienda.id">

                                <td v-text="tienda.nombre"></td>
                                <td v-text="tienda.sitio_web"></td>
                                <td v-text="tienda.telefono"></td>
                                <td v-text="tienda.pais"></td>
                                <td v-text="tienda.descripcion"></td>
                                <td>
                                    <button type="button" class="btn btn-success btn-md" v-if="tienda.condicion">
                                        <i class="fa fa-check fa-1x"></i> Activo
                                    </button>

                                    <button type="button" class="btn btn-danger btn-md" v-else>
                                        <i class="fa fa-check fa-1x"></i> Desactivado
                                    </button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-md" @click="abrirModal('tienda','actualizar',tienda);">
                                        <i class="fa fa-edit fa-1x"></i> Editar
                                    </button> &nbsp;
                                </td>
                                <td>
                                    <template v-if="tienda.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarTienda(tienda.id);">
                                            <i class="fa fa-lock fa-1x"></i> Desactivar
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-succes btn-sm" @click="activarTienda(tienda.id);">
                                            <i class="fa fa-lock fa-1x"></i> Activar
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination,current_page - 1,buscar,criterio);">Anterior</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio);" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio);">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" @click="cerrarModal();" class="close" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div v-show="errorTienda" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjTienda" :key="error" v-text="error"></div>
                            </div>
                        </div>

                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tienda</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de Tienda">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Sitio Web</label>
                                <div class="col-md-9">
                                <input type="email" v-model="sitio_web" class="form-control" placeholder="Ingrese Sitio Web">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                                <div class="col-md-9">
                                <input type="email" v-model="telefono" class="form-control" placeholder="Ingrese Telefono">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Pais</label>
                                <div class="col-md-9">
                                <input type="text" v-model="pais" class="form-control" placeholder="Ingrese Pais">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripcion">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal();" class="btn btn-danger"><i class="fa fa-times fa-2x"></i> Cerrar</button>
                        <button type="button" @click="registrarTienda()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Guardar</button>
                        <button type="button" @click="actualizarTienda()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-2x"></i> Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</template>

<script>

    export default {
        data(){
            return {
                tienda_id:0,
                nombre:'',
                sitio_web:'',
                telefono:'',
                pais:'',
                descripcion:'',
                arrayTienda:[],
                modal:0,
                tituloModal:'',
                tipoAccion:0,
                errorTienda:0,
                errorMostrarMsjTienda:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                criterio:'nombre',
                buscar:''
            }
        },

        computed: {
            isActive: function(){
                return this.pagination.current_page;
            },

            pagesNumber: function(){
                if (!this.pagination.to) {
                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },

        methods:{
            listarTienda(page,buscar,criterio){
                let me=this;

                var url = '/tienda?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio ;

                axios.get(url).then(function (response) {
                    // handle success
                    var respuesta = response.data;
                    me.arrayTienda = respuesta.tiendas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me=this;

                me.pagination.current_page = page;

                me.listarTienda(page,buscar,criterio);
            },

            registrarTienda(){

                if(this.validarTienda()) {
                    return;
                }

                let me=this;

                axios.post('/tienda/registrar',{
                    'nombre':this.nombre,
                    'sitio_web':this.sitio_web,
                    'telefono':this.telefono,
                    'pais':this.pais,
                    'descripcion':this.descripcion

                }).then(function (response) {
                    // handle success
                    //console.log(response);
                    me.cerrarModal();
                    me.listarTienda(1,'','nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            actualizarTienda(){
                if(this.validarTienda()) {
                    return;
                }

                let me=this;

                axios.put('/tienda/actualizar',{
                    'nombre':this.nombre,
                    'sitio_web':this.sitio_web,
                    'telefono':this.telefono,
                    'pais':this.pais,
                    'descripcion':this.descripcion,
                    'id':this.tienda_id

                }).then(function (response) {
                    // handle success
                    //console.log(response);
                    me.cerrarModal();
                    me.listarTienda(1,'','nombre');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            desactivarTienda(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar la tienda?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-1x"></i> Aceptar!',
                cancelButtonText: '<i class="fa fa-times fa-1x"></i> Cancelar!',
                reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        let me=this;

                        axios.put('/tienda/desactivar',{
                            'id':id,

                        }).then(function (response) {
                            // handle success
                            //console.log(response);
                            me.listarTienda(1,'','nombre');
                            swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'La tienda se a desactivado con exito!',
                            'success'
                            )
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                        ) {
                    }
                })
            },

            activarTienda(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de activar la tienda?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-1x"></i> Aceptar!',
                cancelButtonText: '<i class="fa fa-times fa-1x"></i> Cancelar!',
                reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        let me=this;

                        axios.put('/tienda/activar',{
                            'id':id,

                        }).then(function (response) {
                            // handle success
                            //console.log(response);
                            me.listarTienda(1,'','nombre');
                            swalWithBootstrapButtons.fire(
                            'Activado!',
                            'La tienda se activado con exito!',
                            'success'
                            )
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                        ) {
                    }
                })
            },

            validarTienda(){
                this.errorTienda=0;
                this.errorMostrarMsjTienda=[];

                if (!this.nombre) this.errorMostrarMsjTienda.push("* El nombre de la tienda no puede estar vacio");

                if (this.errorMostrarMsjTienda.length) this.errorTienda=1;

                return this.errorTienda;
            },

            cerrarModal(){
                this.modal=0;
                this.tituloModal="";
                this.nombre="";
                this.sitio_web="";
                this.telefono="";
                this.pais="";
                this.descripcion="";
            },

            abrirModal(modelo,accion,data=[]){
                switch (modelo) {
                    case "tienda":
                    {
                        switch (accion) {
                            case "registrar":
                            {
                                this.modal=1;
                                this.tituloModal="Registrar Tienda";
                                this.nombre="";
                                this.sitio_web="";
                                this.telefono="";
                                this.pais="";
                                this.descripcion="";
                                this.tipoAccion=1;
                                break;
                            }
                            case "actualizar":
                            {
                                this.modal=1;
                                this.tituloModal="Editar Tienda";
                                this.tipoAccion=2;
                                this.tienda_id=data["id"];
                                this.nombre=data["nombre"];
                                this.sitio_web=data["sitio_web"];
                                this.telefono=data["telefono"];
                                this.pais=data["pais"];
                                this.descripcion=data["descripcion"];
                                break;
                                //console.log(data);
                            }
                        }
                    }
                }
            }
        },

        mounted() {
            //console.log('Component mounted.')
            this.listarTienda(1,this.buscar,this.criterio);
        }
    }

</script>

<style>
    .modal-content{
        width: 100% !important;
        position:absolute !important;
    }
    .mostrar{
        display:list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display:flex;
        justify-content:center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
        font-size: 20px;
    }

</style>
