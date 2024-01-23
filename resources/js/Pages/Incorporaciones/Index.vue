<template>
    <Head title="Incorporaciones"></Head>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <h5>INCORPORACIONES</h5>
                    </template>
                    <template v-slot:end>
                        <div class="my-2"></div>
                    </template>
                </Toolbar>
                <DataTable />
            </div>
            <div class="card">
                <DataTable
                    v-model:editingRows="editingRows"
                    :value="products"
                    editMode="row"
                    dataKey="id"
                    @row-edit-save="onRowEditSave"
                    :pt="{
                        table: { style: 'min-width: 50rem' },
                        column: {
                            bodycell: ({ state }) => ({
                                style:
                                    state['d_editing'] &&
                                    'padding-top: 0.6rem; padding-bottom: 0.6rem',
                            }),
                        },
                    }"
                >
                    <Column field="item" header="ITEM" style="width: 20%">
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" />
                        </template>
                    </Column>
                    <Column field="gerencia" header="GERENCIA" style="width: 20%">
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" />
                        </template>
                    </Column>
                    <Column field="departameto" header="DEPARTAMENTO" style="width: 20%">
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" />
                        </template>
                    </Column>
                    <Column field="departameto" header="DEPARTAMENTO" style="width: 20%">
                        <template #editor="{ data, field }">
                            <InputText v-model="data[field]" />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout.vue";
export default {
    name: "Index",
    layout: AppLayout,
};
</script>

<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup"; // optional
import Row from "primevue/row"; // optional
import Button from "primevue/button";

const products = ref();
const editingRows = ref([]);

const onRowEditSave = (event) => {
    let { newData, index } = event;

    products.value[index] = newData;
};
</script>

<style lang="scss" scoped></style>
