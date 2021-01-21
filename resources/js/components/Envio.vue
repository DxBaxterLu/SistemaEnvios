<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="/">SISTEMA DE ENVIOS</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <!--Listado-->
                <template v-if="listado==1">
                    <div class="card-header">
                    <h2>Listado de Envios</h2><br/>
                        <button class="btn btn-primary btn-lg" type="button" @click="mostrarDetalle()">
                            <i class="fa fa-plus fa-1x"></i>&nbsp;&nbsp;Nuevo Envio
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_identificacion">Tipo identificación</option>
                                        <option value="num_envio">Número Envio</option>
                                        <option value="fecha_envio">Fecha Envio</option>
                                    </select>
                                    <input type="text"  @keyup.enter="listarEnvio(1,buscar,criterio);" v-model="buscar" class="form-control" placeholder="Buscar texto">
                                    <button type="submit"  @click="listarEnvio(1,buscar,criterio);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">
                                        <th>Ver Detalle</th>
                                        <th>Fecha Envio</th>
                                        <th>Número Envio</th>
                                        <th>Tipo de identificación</th>
                                        <th>Cliente</th>
                                        <th>Total (USD$)</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                        <th>Cambiar estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="envio in arrayEnvio" :key="envio.id">
                                        <td>
                                            <button type="button" @click="verEnvio(envio.id)" class="btn btn-warning btn-sm">
                                            <i class="fa fa-eye fa-1x"></i> Ver detalle
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="envio.fecha_envio"></td>
                                        <td v-text="envio.num_envio"></td>
                                        <td v-text="envio.tipo_identificacion"></td>
                                        <td v-text="envio.usuario"></td>
                                        <td v-text="envio.total"></td>
                                        <td v-text="envio.impuesto"></td>
                                        <td>
                                            <button type="button" v-if="envio.estado=='Enviado'" class="btn btn-success btn-sm">
                                                <i class="fa fa-check fa-1x"></i> Enviado
                                            </button>
                                            <button type="button" v-else class="btn btn-danger btn-sm">
                                                <i class="fa fa-times fa-1x"></i> Anulado
                                            </button>
                                        </td>
                                        <td>
                                            <template v-if="envio.estado=='Enviado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarEnvio(envio.id)">
                                                    <i class="fa fa-times fa-1x"></i> Anular Envio
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-check fa-1x"></i> Cambiado
                                                </button>
                                            </template>
                                        </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado-->
                <!--Detalle-->
                <template v-else-if="listado==0">
                    <span><strong>(*) Campo obligatorio</strong></span><br/>
                    <h3 class="text-center">LLenar el formulario</h3>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Número Envio(*)</strong></label>
                                    <input type="text" class="form-control" v-model="num_envio" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Cliente(*)</strong></label>
                                    <v-select
                                        :on-search="selectUsuario"
                                        label="nombre"
                                        :options="arrayUsuario"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDatosUsuario"
                                    >
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Tipo Identificación(*)</strong></label>
                                    <select class="form-control" v-model="tipo_identificacion">
                                        <option value="0">Seleccione</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="FACTURA">Tracking</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="text-uppercase"><strong>Impuesto(*)</strong></label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div v-show="errorEnvio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEnvio" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Producto <span class="text-error" v-show="idalerta==0">(*Ingrese la descripcion del producto)</span></label>
                                    <div class="form-inline">
                                        <button @click="abrirModal()" class="btn btn-primary">
                                        <i class="fa fa-plus"></i>&nbsp;Agregar Productos
                                        </button>
                                        <input type="text" readonly class="form-control" v-model="alerta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Peso <span class="text-error" v-show="peso==0">(*Ingrese un valor)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="peso">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span class="text-error" v-show="cantidad==0">(*Ingrese un valor)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento </label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-primary form-control btnagregar"><i class="fa fa-plus fa-1x"></i> Agregar detalle</button>
                                </div>
                            </div>
                        </div>
                        <br/><br/>
                        <div class="form-group row border">
                            <h3>Lista de Envios a Clientes</h3>
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-success">
                                            <th>Eliminar</th>
                                            <th>Producto</th>
                                            <th>Peso</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Total (USD$)</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times fa-1x"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.alerta">
                                            </td>
                                            <td>
                                                <input v-model="detalle.peso" type="number" class="form-control" placeholder="LB">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.cantidad}}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.descuento>(detalle.peso*detalle.cantidad)">Descuento superior al total</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detalle.peso*detalle.cantidad - detalle.descuento}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="5" align="right"><strong>Sub-Total:</strong></td>
                                            <td><strong> USD$ {{subTotal=(total-subTotalImpuesto).toFixed(2)}}</strong></td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="5" align="right"><strong>Impuesto:</strong></td>
                                            <td><strong>USD$ {{subTotalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</strong></td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="5" align="right"><strong>Total:</strong></td>
                                            <td><strong>USD$ {{total=calcularTotal}}</strong></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No se han agregado productos
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" @click="ocultarDetalle()"><i class="fa fa-times fa-1x"></i> Cerrar</button>
                                <button type="button" class="btn btn-success" @click="registrarEnvio()"><i class="fa fa-save fa-1x"></i> Registrar Envio</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin Detalle-->
                <!-- Ver Envio -->
                <template v-else-if="listado==2">
                    <h2 class="text-center">Detalle de Envio</h2><br/>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Cliente</strong></label>
                                    <p v-text="usuario"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Tipo de identificación</strong></label>
                                    <p v-text="tipo_identificacion"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Número Envio</strong></label>
                                    <p v-text="num_envio"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-uppercase"><strong>Impuesto</strong></label>
                                    <p v-text="impuesto"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-success">
                                            <th>Alerta</th>
                                            <th>Costo (USD$)</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Total (USD$)</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.alerta">
                                            </td>
                                            <td v-text="detalle.costo">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{detalle.costo*detalle.cantidad - detalle.descuento}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="4" align="right"><strong>Sub-Total:</strong></td>
                                            <td><strong>USD$ {{subTotal=(total-subTotalImpuesto).toFixed(2)}}</strong></td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="4" align="right"><strong>Impuesto:</strong></td>
                                            <td><strong>USD$ {{subTotalImpuesto=((total*impuesto)).toFixed(2)}}</strong></td>
                                        </tr>
                                        <tr style="background-color: grey;">
                                            <td colspan="4" align="right"><strong>Total:</strong></td>
                                            <td><strong>USD$ {{total}}</strong></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No se han agregado alertas
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-danger"><i class="fa fa-times fa-1x"></i> Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioP">
                                    <option value="alerta">Alertas</option>
                                    <option value="descripcion">Descripcion</option>
                                    </select>
                                    <input type="text"  @keyup.enter="listarAlerta(buscarP,criterioP);" v-model="buscarP" class="form-control" placeholder="Buscar texto">
                                    <button type="submit"  @click="listarAlerta(buscarP,criterioP);" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th>Categoria</th>
                                            <th>Transporte</th>
                                            <th>Tienda</th>
                                            <th>Descripcion</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="alerta in arrayAlerta" :key="alerta.id">
                                            <td v-text="alerta.nombre_categoria"></td>
                                            <td v-text="alerta.nombre_transporte"></td>
                                            <td v-text="alerta.nombre_tienda"></td>
                                            <td v-text="alerta.descripcion"></td>
                                            <td v-text="alerta.stock"></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" v-if="alerta.condicion">
                                                    <i class="fa fa-unlock"></i>&nbsp;Activo
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm" v-else>
                                                    <i class="fa fa-lock"></i>&nbsp;Desactivado
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(alerta)" class="btn btn-primary btn-sm">
                                                <i class="fa fa-plus fa-1x"></i> Agregar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-danger"><i class="fa fa-times fa-1x"></i> Cerrar</button>
                        <button type="button" @click="registrarUsuario()" v-if="tipoAccion==1" class="btn btn-success"><i class="fa fa-save fa-1x"></i> Guardar</button>
                        <button type="button" @click="actualizarUsuario()" v-if="tipoAccion==2" class="btn btn-success"><i class="fa fa-save fa-1x"></i> Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
    export default {
        data(){
            return {
                envio_id: 0,
                idusuario:0,
                usuario:0,
                tipo_identificacion : 'Tracking',
                num_envio : '',
                impuesto: 0.20,
                total:0.0,
                subTotalImpuesto: 0.0,
                subTotal: 0.0,
                arrayEnvio : [],
                arrayUsuario: [],
                arrayDetalle : [],
                listado:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEnvio : 0,
                errorMostrarMsjEnvio : [],
                pagination:{
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset:3,
                criterio:'num_envio',
                buscar:'',
                criterioP:'nombre',
                buscarP: '',
                arrayAlerta: [],
                idalerta: 0,
                alerta: '',
                peso:0,
                descripcion: '',
                cantidad:0,
                descuento:0,
                stock:0
            }

        },

        components: {
            vSelect
        },

        computed:{
            isActived: function(){
            return this.pagination.current_page;
            },

            //calcula los elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return[];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
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
            },

            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].costo*this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
                }
                return resultado;
            }

        },

        methods:{

        listarEnvio(page,buscar,criterio){

            let me=this;
            var url= '/envio?page=' + page + '&buscar='+ buscar + '&criterio='+criterio;
            axios.get(url).then(function (response) {
                // handle success
                //console.log(response);
                var respuesta = response.data;
                me.arrayEnvio=respuesta.envios.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
        },

        selectUsuario(search,loading){
            let me=this;
            loading(true)

            var url= '/user/selectUsuario?filtro='+search;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                q: search
                me.arrayUsuario=respuesta.usuarios;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        getDatosUsuario(val1){
            let me = this;
            me.loading = true;
            me.idusuario= val1.id;
        },

        buscarAlerta(){
            let me=this;
            var url= '/alerta/buscarAlertaEnvio?filtro=' + me.descripcion;

            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayAlerta = respuesta.alertas;

                if (me.arrayAlerta.length>0){
                    me.descripcion=me.arrayAlerta[0]['descripcion'];
                    me.idalerta=me.arrayAlerta[0]['id'];
                    me.peso=me.arrayAlerta[0]['peso'];
                    me.stock=me.arrayAlerta[0]['stock'];
                }
                else{
                    me.alerta='No existe el producto';
                    me.idalerta=0;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },


        cambiarPagina(page,buscar,criterio){
            let me = this;
            //Actualiza  la pagina actual
            me.pagination.current_page=page;
            me.listarEnvio(page,buscar,criterio);

        },

        encuentra(id){
            var sw=0;
            for(var i=0;i<this.arrayDetalle.length;i++){
                if(this.arrayDetalle[i].idalerta==id){
                    sw=true;
                }
            }
            return sw;
        },

        eliminarDetalle(index){
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },

        agregarDetalle(){
        let me=this;
            if(me.idalerta==0 || me.cantidad==0 || me.peso==0){
            }

            else{
                if(me.encuentra(me.idalerta)){
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese alerta ya fue agregado',
                        })
                }
                else{
                    if(me.cantidad>me.stock){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible',
                            })
                    }
                    else{
                        me.arrayDetalle.push({
                                idalerta: me.idalerta,
                                descripcion: me.descripcion,
                                cantidad: me.cantidad,
                                costo: me.costo,
                                descuento: me.descuento,
                                stock: me.stock
                            });
                            me.descripcion="";
                            me.idalerta=0;
                            me.alerta="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0
                    }
                }
            }
        },

        agregarDetalleModal(data=[]){

            let me=this;

            if(me.encuentra(data['id'])){
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese producto ya fue agregado',
                        })
                }
                else{
                me.arrayDetalle.push({
                        idalerta: data['id'],
                        descripcion: data['descripcion'],
                        cantidad: 1,
                        precio: data['precio_venta'],
                        descuento:0,
                        stock:data['stock']
                    });
                }

        },

        listarProducto (buscar,criterio){
            let me=this;
            var url= '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayProducto = respuesta.productos.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

    registrarVenta(){
        if(this.validarVenta()){

            return;
        }

        let me = this;
        axios.post('/venta/registrar',{
            'idcliente': this.idcliente,
            'tipo_identificacion': this.tipo_identificacion,
            'num_venta' : this.num_venta,
            'impuesto' : this.impuesto,
            'total' : this.total,
            'data': this.arrayDetalle
        }).then(function (response) {
            me.listado=1;
            me.listarVenta(1,'','num_venta');
            me.idcliente=0;
            me.tipo_identificacion='FACTURA';
            me.num_venta='';
            me.impuesto=0.20;
            me.total=0.0;
            me.idproducto=0;
            me.producto='';
            me.cantidad=0;
            me.precio=0;
            me.stock=0;
            me.codigo='';
            me.descuento=0;
            me.arrayDetalle=[];
        }).catch(function (error) {
            console.log(error);
        });
    },


            validarVenta(){

                let me=this;
                me.errorVenta=0;
                me.errorMostrarMsjVenta =[];
                var prod;

                me.arrayDetalle.map(function(x){
                    if (x.cantidad>x.stock){
                        prod=x.producto + " con stock insuficiente";
                        me.errorMostrarMsjVenta.push(prod);
                    }
                });

                if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");
                if (me.tipo_identificacion==0) me.errorMostrarMsjVenta.push("Seleccione la identificación");
                if (!me.num_venta) me.errorMostrarMsjVenta.push("Ingrese el número de venta");
                if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de venta");
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese detalles");

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },

            mostrarDetalle(){

                let me= this;

                    me.listado=0;
                    me.idproveedor=0;
                    me.tipo_identificacion='FACTURA';
                    me.num_compra='';
                    me.impuesto=0.20;
                    me.total=0.0;
                    me.idproducto=0;
                    me.producto='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];

            },
            ocultarDetalle(){
                this.listado=1;
            },

            verVenta(id){

                let me=this;
                me.listado=2;

                //Obtener los datos de la compra
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;

                    me.cliente = arrayVentaT[0]['nombre'];
                    me.tipo_identificacion=arrayVentaT[0]['tipo_identificacion'];
                    me.num_venta=arrayVentaT[0]['num_venta'];
                    me.impuesto=arrayVentaT[0]['impuesto'];
                    me.total=arrayVentaT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles
                var urld= '/venta/obtenerDetalles?id=' + id;

                axios.get(urld).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

        cerrarModal(){

                this.modal=0;
                this.tituloModal='';

        },

        abrirModal(){

            this.arrayProducto=[];
            this.modal = 1;
            this.tituloModal = 'Seleccione uno o varios productos';

        },

            desactivarVenta(id){
            swal({
                title: 'Esta seguro de anular la venta?',
                //type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fa fa-check fa-1x"></i> Aceptar!',
                cancelButtonText: '<i class="fa fa-times fa-1x"></i> Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','num_venta');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },


        },

        mounted() {
            //console.log('Component mounted.')
            this.listarEnvio(1,this.buscar,this.criterio);
        }
    }
</script>

<style>

.modal-content{

    width:100% !important;
    position:absolute !important;
}

.mostrar{

    display:list-item !important;
    opacity:1 !important;
    position:absolute !important;
    background-color:#3c29297a !important;
}

.div-error{

    display:flex;
    justify-content:center;
}

.text-error{

    color:red !important;
    font-weight:bold;
    font-size:20px;
}

@media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
