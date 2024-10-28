@extends('erp.base')

@section('content')
    <div id="app">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content">
            <!--begin::Card-->
            <div class="card card-flush" id="content-card">
                <!--begin::Card header-->
                <div class="card-header">
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
                    <!--begin::Table-->
                    <v-client-table v-model="sucursales" :columns="columns" :options="options">
                        <div slot="acciones" slot-scope="props">
                            <button type="button" class="btn btn-icon btn-sm btn-info" title="Ver/Editar Sucursal" data-bs-toggle="modal" data-bs-target="#kt_modal_add_sucursal" @click.prevent="selectSucursal(props.row)">
                                <i class="fas fa-pencil"></i>
                            </button>

                            <button type="button" title="Eliminar" data-kt-indicator="off" class="btn btn-icon btn-sm btn-danger" @click="deleteSucursal(props.row.id)" :data-kt-indicator="props.row.eliminando ? 'on' : 'off'">
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
                        <h2 class="fw-bold" v-if="isEdit" v-text="isEdit ? 'Actualizar sucursal' : 'Crear sucursal'"></h2>
                        <!--begin::Close-->
                        <div class="btn btn-close" data-bs-dismiss="modal"></div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form id="kt_modal_add_sucursal_form" class="form" action="#" @submit.prevent="">
                            <div class="row mb-5 fv-row">
                                <label class="required fw-semibold fs-6">Sucursal</label>
                                <input type="text" class="form-control" placeholder="Sucursal" v-model="sucursal_model.nombre" name="sucursal"/>
                            </div>
                            <div class="row mb-5 fv-row">
                                <label class="required fw-semibold fs-6">Dirección</label>
                                <input type="text" class="form-control" placeholder="Dirección" v-model="sucursal_model.direccion" name="direccion"/>
                            </div>
                            <!--begin::Actions-->
                            <div class="text-end pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" title="Eliminar" data-kt-indicator="off" class="btn btn-secondary" @click="saveSucursal" :disabled="loading" :data-kt-indicator="loading ? 'on' : 'off'">
                                    <span class="indicator-label" v-text="isEdit ? 'Actualizar' : 'Crear'"></span>
                                    <span class="indicator-progress">[[isEdit ? 'Actualizando' : 'Creando']] <span class="spinner-border spinner-border-sm align-middle"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
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
                columns: ['id', 'nombre', 'direccion', 'acciones'],
                options: {
                    headings: {
                        id: 'ID',
                        nombre: 'Sucursal',
                        direccion: 'Dirección',
                        acciones: 'Acciones',
                    },
                    columnsClasses: {
                        id: 'align-middle px-2 ',
                        nombre: 'align-middle ',
                        direccion: 'align-middle ',
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

                sucursal_model: {},

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
                    vm.sucursal_model = {};
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
                                        direccion: vm.sucursal_model.direccion
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
