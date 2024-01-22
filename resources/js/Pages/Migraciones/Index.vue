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
            <Card>
                <template #header><strong>Filtros</strong></template>
                <template #content>
                    <div class="grid">
                        <div class="col-12">
                            <label class="form-control-label text-secondary"
                                ><i class="pi pi-users" aria-hidden="true"></i>
                                Persona</label
                            >
                        </div>
                        <div class="col-12 flex flex-wrap align-items-center">
                            <div style="width: calc(100% - 31px)">
                                <vue-simple-typeahead
                                    :key="filterComKey"
                                    class="p-inputtext text-xs py-2 w-full"
                                    :items="autoCompleteValues"
                                    :min-input-length="1"
                                    :item-projection="(item) => item.text"
                                    placeholder="Buscar por nombre, item..."
                                    @select-item="onSelectItem"
                                    @on-input="onInputChange"
                                />
                            </div>
                            <div class="ms-0">
                                <Button
                                    v-if="item"
                                    icon="pi pi-trash"
                                    severity="danger"
                                    rounded
                                    @click="cleanFiltroSelect()"
                                >
                                </Button>
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
                            <Dropdown
                                v-model="estado"
                                :options="[
                                    { name: 'Ocupado', value: 'Ocupado' },
                                    { name: 'Acefalia', value: 'Acefalia' },
                                ]"
                                optionLabel="name"
                                option-value="value"
                                placeholder="Estado"
                                class="w-full"
                                @change="onFilter"
                                show-clear
                            />
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-control-label text-secondary">
                                <i
                                    class="pi pi-building"
                                    aria-hidden="true"
                                />
                                Gerencias
                                <strong class="mx-3">></strong>
                                <i
                                    class="pi pi-sitemap"
                                    aria-hidden="true"
                                />
                                Departamentos
                            </label>
                        </div>
                        <div class="col-12">
                            <Tree
                                v-model:selectionKeys="value"
                                filter
                                :value="gerenciasNode"
                                selectionMode="checkbox"
                                class="w-full tree-gerencias"
                            ></Tree>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <div class="col-8">
            <Card>
                <template #header
                    ><strong class="m-2">Puestos</strong></template
                >
                <template #content>
                    <div class="grid">
                        <div class="col-12">
                            <div class="flex flex-wrap justify-content-evenly">
                                <CardPuesto v-for="puesto in puestos" :key="puesto.item" :puesto="puesto" />
                            </div>
                        </div>
                        <div class="col-12">
                            <Paginator
                                :key="limit"
                                :rows="limit"
                                :totalRecords="total"
                                :rowsPerPageOptions="[6, 9, 18, 36]"
                                :template="{
                                    '640px':
                                        'PrevPageLink CurrentPageReport NextPageLink',
                                    '960px':
                                        'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
                                    '1300px':
                                        'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink',
                                    default:
                                        'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown JumpToPageInput',
                                }"
                                @page="onPaginate"
                            ></Paginator>
                        </div>
                    </div>
                </template>
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
import Dropdown from "primevue/dropdown";
import Tree from "primevue/tree";
import Paginator from "primevue/paginator";
import CardPuesto from "./Components/CardPuesto.vue";
import VueSimpleTypeahead from "vue3-simple-typeahead";
import "vue3-simple-typeahead/dist/vue3-simple-typeahead.css"; //Optional default CSS
import axios from "axios";

const props = defineProps({
    gerencias: {
        type: Array,
        required: true,
    },
});

const gerenciasNode = computed(() => props.gerencias);

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

watch(gerenciasIds, () => {
    onFilter();
});

watch(departamentosIds, () => {
    onFilter();
});

// Paginacion
const page = ref(0);
const limit = ref(6);
const total = ref(0);
const lastPage = ref(1);

function onPaginate(pageEvent) {
    console.log('Page event data:', pageEvent);
    if(pageEvent.rows !== limit.value) {
        limit.value = pageEvent.rows;
        page.value = 0;
    } else {
        page.value = pageEvent.page;
    }
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
        page: page.value + 1,
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
.tree-gerencias {
    height: 450px;
    overflow: hidden;
    overflow-y: scroll;
}
</style>
