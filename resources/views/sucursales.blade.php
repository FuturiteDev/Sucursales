@extends('erp.base')

@section('content')
    <div id="app">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content">
            <!--begin::Card-->
            <div class="card card-flush" id="content-card">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title flex-column">
                        <h3 class="ps-2">Listado de Sucursales</h3>
                    </div>
                    <div class="card-toolbar">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sucursal" @click="isEdit = false">
                            <i class="ki-outline ki-plus fs-2"></i> Crear Sucursal
                        </a>
                    </div>
                </div>
                <!--end::Card toolbar-->

                <!--begin::Card body-->
                <div class="card-body py-4">
                    <div class="px-4 py-2">
                        <button class="btn btn-success" @click="syncShopify" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sincronizar con Shopify">
                            Sincronizar <i class="ki-solid ki-arrows-circle"></i>
                        </button>
                    </div>
                    <!--begin::Table-->
                    <v-client-table v-model="sucursales" :columns="columns" :options="options">
                        <div slot="matriz" slot-scope="props">
                            <i v-if="props.row.matriz == 1" class="ki-solid ki-star fs-4"></i>
                        </div>
                        <div slot="color" slot-scope="props">
                            <div v-if="props.row.color" class="h-25px w-50px" :style="{'background-color': '#' + (props.row.color.slice(4) ?? '000000')}"></div>
                        </div>
                        <div slot="acciones" slot-scope="props">
                            <button type="button" class="btn btn-icon btn-sm btn-success" title="Ver/Editar Sucursal" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sucursal" @click.prevent="selectSucursal(props.row)">
                                <i class="fas fa-pencil"></i>
                            </button>
                            <button type="button" title="Eliminar" class="btn btn-icon btn-sm btn-danger" @click="deleteSucursal(props.row.id)" :data-kt-indicator="props.row.eliminando ? 'on' : 'off'">
                                <span class="indicator-label"><i class="fas fa-trash-alt"></i></span>
                                <span class="indicator-progress"><span class="spinner-border spinner-border-sm align-middle"></span></span>
                            </button>
                        </div>
                    </v-client-table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content-->

        <!--begin::Modal - Add task-->
        <div class="modal fade" id="kt_modal_add_sucursal" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <h2 class="fw-bold" v-text="isEdit ? 'Actualizar sucursal' : 'Crear sucursal'"></h2>
                        <!--begin::Close-->
                        <div class="btn btn-close" data-bs-dismiss="modal"></div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form id="kt_modal_add_sucursal_form" class="form" action="#" @submit.prevent="">
                            <div class="row g-7">
                                <div class="col-12 fv-row">
                                    <label class="required fw-semibold fs-6 ms-2">Sucursal</label>
                                    <input type="text" class="form-control" placeholder="Sucursal" v-model="sucursal_model.nombre" name="sucursal"/>
                                </div>
                                <div class="col-12 fv-row">
                                    <label class="required fw-semibold fs-6 ms-2">Dirección</label>
                                    <input type="text" class="form-control" placeholder="Dirección" v-model="sucursal_model.direccion" name="direccion"/>
                                </div>
                                <div class="col-12 fv-row">
                                    <label class="fw-semibold fs-6 ms-2">Teléfono</label>
                                    <input type="tel" class="form-control" placeholder="Teléfono" v-model="sucursal_model.telefono" name="telefono"/>
                                </div>
                                <div class="col-12 fv-row">
                                    <label class="fw-semibold fs-6 ms-2">WhatsApp</label>
                                    <input type="tel" class="form-control" placeholder="WhatsApp" v-model="sucursal_model.whatsapp" name="whatsapp"/>
                                </div>
                                <div class="col-12 fv-row">
                                    <label class="fw-semibold fs-6 ms-2">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" v-model="sucursal_model.email" name="email"/>
                                </div>
                                <div class="col-12 fv-row">
                                    <label class="required fw-semibold fs-6 ms-2">Color asignado</label>
                                    <input type="color" id="color" name="color" class="align-middle" v-model="sucursal_model.color" />
                                </div>
                                <div class="col-12 fv-row">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" v-model="sucursal_model.matriz" name="matriz"/>
                                        <label class="fw-semibold">Matriz</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Actions-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="saveSucursal" :disabled="loading" :data-kt-indicator="loading ? 'on' : 'off'">
                            <span class="indicator-label" v-text="isEdit ? 'Actualizar' : 'Crear'"></span>
                            <span class="indicator-progress">[[isEdit ? 'Actualizando' : 'Creando']] <span class="spinner-border spinner-border-sm align-middle"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Add task-->

    </div>
@endsection

@section('scripts')
    <script src="/common_assets/js/vue-tables-2.min.js"></script>

    <script>
        const app = new Vue({
            el: '#app',
            delimiters: ['[[', ']]'],
            data: () => ({
                sucursales: [],
                columns: ['matriz', 'id', 'nombre', 'color', 'direccion', 'telefono', 'whatsapp', 'email', 'acciones'],
                options: {
                    headings: {
                        matriz: '',
                        id: 'ID',
                        nombre: 'Sucursal',
                        nombre: 'Color',
                        direccion: 'Dirección',
                        telefono: 'Telefono',
                        whatsapp: 'WhatsApp',
                        email: 'Email',
                        acciones: 'Acciones',
                    },
                    columnsClasses: {
                        matriz: 'align-middle px-2 ',
                        id: 'align-middle px-2 ',
                        nombre: 'align-middle ',
                        color: 'align-middle text-center ',
                        direccion: 'align-middle ',
                        email: 'align-middle text-center ',
                        telefono: 'align-middle text-center ',
                        whatsapp: 'align-middle text-center ',
                        acciones: 'align-middle text-center px-2 ',
                    },
                    sortable: ['nombre', 'direccion'],
                    filterable: ['nombre', 'direccion'],
                    skin: 'table table-sm table-rounded table-striped border align-middle table-row-bordered fs-6',
                    columnsDropdown: true,
                    resizableColumns: false,
                    sortIcon: {
                        base: 'ms-3 fas',
                        up: 'fa-sort-asc text-gray-400',
                        down: 'fa-sort-desc text-gray-400',
                        is: 'fa-sort text-gray-400',
                    },
                    texts: {
                        count: "Mostrando {from} de {to} de {count} registros|{count} registros|Un registro",
                        first: "Primera",
                        last: "Última",
                        filterPlaceholder: "Buscar...",
                        limit: "Registros:",
                        page: "Página:",
                        noResults: "No se encontraron resultados",
                        loading: "Cargando...",
                        columns: "Columnas",
                    },
                },

                sucursal_model: {
                    color: "#000000",
                },

                validator: null,
                isEdit: false,
                loading: false,
                blockUI: null,
                requestGet: null,

            }),
            mounted() {
                let vm = this;
                vm.$forceUpdate();

                let container = document.querySelector('#content-card');
                if (container) {
                    vm.blockUI = new KTBlockUI(container);
                }
                vm.getSucursales(true);
                vm.formValidate();
                $("#kt_modal_add_sucursal").on('hidden.bs.modal', event => {
                    vm.validator.resetForm();
                    vm.sucursal_model = {
                        color:"#000000",
                    };
                });
            },
            methods: {
                getSucursales(showLoader) {
                    let vm = this;
                    if (showLoader) {
                        if (!vm.blockUI) {
                            let container = document.querySelector('#content-card');
                            if (container) {
                                vm.blockUI = new KTBlockUI(container);
                                vm.blockUI.block();
                            }
                        } else {
                            if (!vm.blockUI.isBlocked()) {
                                vm.blockUI.block();
                            }
                        }
                    }

                    if (vm.requestGet) {
                        vm.requestGet.abort();
                        vm.requestGet = null;
                    }

                    vm.loading = true;

                    vm.requestGet = $.ajax({
                        url: '/api/sucursales/all',
                        type: 'GET',
                    }).done(function (res) {
                        vm.sucursales = res.results;
                    }).fail(function (jqXHR, textStatus) {
                        if (textStatus != 'abort') {
                            console.log("Request failed getSucursales: " + textStatus, jqXHR);
                        }
                    }).always(function () {
                        vm.loading = false;

                        if (vm.blockUI && vm.blockUI.isBlocked()) {
                            vm.blockUI.release();
                        }
                    });
                },
                syncShopify(){
                    let vm = this;
                    const loadingEl = document.createElement("div");
                    document.body.prepend(loadingEl);
                    loadingEl.classList.add("page-loader");
                    loadingEl.classList.add("bg-gray-900");
                    loadingEl.classList.add("bg-opacity-50");
                    loadingEl.innerHTML = `
                    <span class="bg-white d-flex flex-center flex-column px-10 py-5 rounded">
                    <span class="spinner-border text-primary" role="status"></span>
                    <span class="fs-6 fw-semibold mt-5 text-gray-800">Sincronizando...</span>
                    </span>`;
                    KTApp.showPageLoading();
                    
                    $.get('/api/shopify/sucursales', res => {

                    }, 'json').always(function() {
                        KTApp.hidePageLoading();
                        loadingEl.remove();
                        vm.getSucursales(true);
                    });
                },
                saveSucursal() {
                    let vm = this;
                    if (vm.validator) {
                        vm.validator.validate().then(function (status) {
                            if (status == 'Valid') {
                                vm.loading = true;
                                $.ajax({
                                    method: "POST",
                                    url: "/api/sucursales/save",
                                    data: {
                                        sucursal_id: vm.isEdit ? vm.sucursal_model.id : null,
                                        nombre: vm.sucursal_model.nombre,
                                        direccion: vm.sucursal_model.direccion,
                                        telefono: vm.sucursal_model.telefono,
                                        whatsapp: vm.sucursal_model.whatsapp,
                                        email: vm.sucursal_model.email,
                                        matriz: vm.sucursal_model.matriz ? 1 : 0,
                                        color: '0xFF' + vm.sucursal_model.color.slice(1),
                                    }
                                }).done(function (res) {
                                    if (res.status === true) {
                                        Swal.fire(
                                            "¡Guardado!",
                                            "Los datos de la sucursal se han almacenado con éxito",
                                            "success"
                                        );
                                        vm.sucursales = res.results;
                                        $('#kt_modal_add_sucursal').modal('hide');
                                    } else {
                                        Swal.fire(
                                            "¡Error!",
                                            res?.message ?? "No se pudo crear la sucursal",
                                            "warning"
                                        );
                                    }
                                }).fail(function (jqXHR, textStatus) {
                                    console.log("Request failed saveSucursal: " + textStatus, jqXHR);
                                    Swal.fire("¡Error!", "Ocurrió un error inesperado al procesar la solicitud. Por favor, inténtelo nuevamente.", "error");
                                }).always(function (event, xhr, settings) {
                                    vm.loading = false;
                                });
                            }
                        });
                    }
                },
                deleteSucursal(idSucursal) {
                    let vm = this;
                    Swal.fire({
                        title: '¿Estas seguro de que deseas eliminar el registro de la sucursal?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, eliminar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            vm.loading = true;
                            let index = vm.sucursales.findIndex(item => item.id == idSucursal);
                            if(index >= 0){
                                vm.$set(vm.sucursales[index], 'eliminando', true);
                            }
                            $.ajax({
                                method: "POST",
                                url: "/api/sucursales/delete",
                                data: {
                                    sucursal_id: idSucursal
                                }
                            }).done(function (res) {
                                if (res.status === true) {
                                    Swal.fire(
                                        'Registro eliminado',
                                        'El registro de la sucursal ha sido eliminado con éxito',
                                        'success'
                                    );
                                    vm.sucursales = res.results;
                                } else {
                                    Swal.fire(
                                        "¡Error!",
                                        res?.message ?? "No se pudo crear la sucursal",
                                        "warning"
                                    );
                                }
                            }).fail(function (jqXHR, textStatus) {
                                console.log("Request failed deleteSucursal: " + textStatus, jqXHR);
                                Swal.fire("¡Error!", "Ocurrió un error inesperado al procesar la solicitud. Por favor, inténtelo nuevamente.", "error");

                                index = vm.sucursales.findIndex(item => item.id == idSucursal);
                                if(index >= 0){
                                    vm.$set(vm.sucursales[index], 'eliminando', false);
                                }
                            }).always(function (event, xhr, settings) {
                                vm.loading = false;
                            });
                        }
                    })
                },
                selectSucursal(sucursal) {
                    this.isEdit = true;
                    this.sucursal_model = {
                        id: sucursal.id,
                        nombre: sucursal.nombre,
                        direccion: sucursal.direccion,
                        telefono: sucursal.telefono,
                        whatsapp: sucursal.whatsapp,
                        email: sucursal.email,
                        matriz: sucursal.matriz == 1 ? true : false,
                        color: '#' + sucursal.color?.slice(4) ?? "#000000",
                    };
                },
                formValidate() {
                    if(this.validator) {
                        this.validator.destroy();
                        this.validator = null;
                    }

                    this.validator = FormValidation.formValidation(
                        document.getElementById('kt_modal_add_sucursal_form'), {
                            fields: {
                                'sucursal': {
                                    validators: {
                                        notEmpty: {
                                            message: 'Nombre requerido',
                                            trim: true
                                        }
                                    }
                                },
                                'direccion': {
                                    validators: {
                                        notEmpty: {
                                            message: 'Direccíon requerida',
                                            trim: true
                                        }
                                    }
                                },
                                'color': {
                                    validators: {
                                        notEmpty: {
                                            message: 'Color requerido',
                                            trim: true
                                        }
                                    }
                                },
                            },
                            plugins: {
                                trigger: new FormValidation.plugins.Trigger(),
                                bootstrap: new FormValidation.plugins.Bootstrap5({
                                    rowSelector: '.fv-row',
                                    eleInvalidClass: '',
                                    eleValidClass: ''
                                })
                            },
                        }
                    );
                },
            },
        });

        Vue.use(VueTables.ClientTable);
    </script>
@endsection
