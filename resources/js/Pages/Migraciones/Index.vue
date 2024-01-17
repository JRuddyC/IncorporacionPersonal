<template>
    <Head title="Planilla"></Head>
    <div class="grid">
        <div class="col-12 p-0">
            <Toolbar class="toolbar-card mb-4">
                <template v-slot:start>
                    <h5>Planilla del Personal</h5>
                </template>
                <template v-slot:end>
                    <div class="my-2">
                        <Button
                            label="Planilla"
                            class="p-button-primary mr-2"
                            @click="openModalImport()"
                        />
                        <Button
                            label="Imagenes"
                            class="p-button-secondary"
                            @click="openModalImportFotos()"
                        />
                    </div>
                </template>
            </Toolbar>
        </div>
        <div class="col-4">
            <Card title="Filtros">
                <template #header><strong>Filtros</strong></template>
                <template #content>
                    <div class="grid">
                        <div class="col-12">
                            <label class="form-control-label text-secondary"
                                ><i class="pi pi-users" aria-hidden="true"></i>
                                Persona</label
                            >
                        </div>
                        <div class="col-12">
                            <div style="width: 95%">
                                <vue-simple-typeahead
                                    :key="filterComKey"
                                    class="form-control form-control-alternative text-xs py-2"
                                    :items="autoCompleteValues"
                                    :min-input-length="1"
                                    :item-projection="(item) => item.text"
                                    placeholder="Buscar por nombre, item..."
                                    @select-item="onSelectItem"
                                    @on-input="onInputChange"
                                />
                            </div>
                            <div class="ms-0">
                                <button
                                    v-if="item"
                                    class="btn btn-xs btn-danger m-0 py-1 px-2"
                                    @click="cleanFiltroSelect()"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                                <span
                                    v-else
                                    class="input-group-text text-body form-control form-control-alternative tex-s"
                                    ><i
                                        class="fas fa-search"
                                        aria-hidden="true"
                                    ></i
                                ></span>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-control-label text-secondary"
                                ><i class="pi pi-tags" aria-hidden="true"></i>
                                Estado</label
                            >
                        </div>
                        <div class="col-12">
                            <Dropdown v-model="estado" :options="[{name:'Ocupado',value:'Ocupado'},{name:'Acefalia',value:'Acefalia'}]" optionLabel="name" option-value="value" placeholder="Estado" class="w-full" show-clear />
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-control-label text-secondary"
                                ><i class="pi pi-building" aria-hidden="true"></i>
                                Gerencias</label
                            >
                        </div>
                        <div class="col-12">
                            <Tree v-model:selectionKeys="value" :value="gerenciasNode" selectionMode="checkbox" class="w-full"></Tree>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <div class="col-8">
            <Card title="Puestos">
                <template #header
                    ><strong class="m-2">Puestos</strong></template
                >
            </Card>
        </div>
        <ImportarModal ref="refImportarModal" />
        <ImportarFotos ref="refImportarFotos" />
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout.vue";
import Button from "primevue/button";
import Card from "primevue/card";

export default {
    name: "Index",
    layout: AppLayout,
};
</script>

<script setup>
import { ref, computed, watch } from "vue";
import ImportarModal from "./Components/ImportarModal.vue";
import ImportarFotos from "./Components/ImportarFotos.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Toolbar from "primevue/toolbar";
import Dropdown from 'primevue/dropdown';
import Tree from 'primevue/tree';
import VueSimpleTypeahead from "vue3-simple-typeahead";
import "vue3-simple-typeahead/dist/vue3-simple-typeahead.css"; //Optional default CSS
import axios from "axios";

const props = defineProps({
    gerencias: {
        type: Array,
        required: true,
    }
});

const gerenciasNode = computed(()=>props.gerencias)

const refImportarModal = ref();

const refImportarFotos = ref();

function openModalImport() {
    refImportarModal.value?.open();
}
function openModalImportFotos() {
    refImportarFotos.value?.open();
}

const value = ref([]);
const gerenciasIds = computed(() =>
    Object.keys(value.value)
        .filter((el) => el.includes("g-"))
        .map((el) => {
            return parseInt(el.replace("g-", ""));
        })
);
const departamentosIds = computed(() =>
    Object.keys(value.value)
        .filter((el) => el.includes("d-"))
        .map((el) => Number(el.replace("d-", "")))
);
// const gerencias = []; pasado por props desde controller
const estado = ref();
const tipoMovimiento = ref();
const puestos = ref([]);

// filtrar autoComplete
const query = ref();
const filterComKey = ref(1);
const item = computed(() => {
    return query.value?.item;
});
const autoCompleteValues = ref([]);

function onSelectItem(item) {
    query.value = item;
    onFilter();
}
function cleanFiltroSelect() {
    query.value = undefined;
    filterComKey.value = parseInt(Math.random() * 100);
    onFilter();
}
function onInputChange(e) {
    axios
        .post("/api/persona-puesto/filtrar", {
            keyword: e.input,
        })
        .then(function (response) {
            if (response.data) {
                autoCompleteValues.value = response.data.elementos;
            }
        })
        .catch(function (error) {
            console.log("error:", error.data);
        });
}

watch(gerenciasIds, (newVal, oldVal) => {
    onFilter();
});

watch(departamentosIds, (newVal, oldVal) => {
    onFilter();
});

// Paginacion
const page = ref(1);
const limit = ref(9);
const total = ref(0);
const lastPage = ref(1);

function onPaginate(pageNumber) {
    page.value = pageNumber;
    onFilter();
}

// Filtros
function onFilter() {
    const filtros = {
        gerenciasIds: gerenciasIds.value,
        departamentosIds: departamentosIds.value,
        item: item.value,
        estado: estado.value,
        tipoMovimiento: tipoMovimiento.value,
        // Paginacion
        page: page.value,
        limit: limit.value,
    };

    axios
        .post("/api/persona-puesto/listar", filtros)
        .then(function (response) {
            if (response.data?.data) {
                puestos.value = response.data?.data;
            }
            if (response.data?.total) {
                total.value = response.data?.total;
            }
            if (response.data?.last_page) {
                lastPage.value = response.data?.last_page;
            }
        })
        .catch(function (error) {
            console.log("error:", error.data);
        });
}

onFilter();
</script>

<style lang="scss" scoped>
.toolbar-card {
    background-color: var(--surface-card);
}
</style>
