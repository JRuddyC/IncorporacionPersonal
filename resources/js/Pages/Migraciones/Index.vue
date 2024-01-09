<template>
    <Head title="Migraciones"></Head>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <h5>MIGRACIONES</h5>
                    </template>
                    <template v-slot:end>
                        <div class="my-2">
                            <Button
                                label="IMPORTAR"
                                icon="pi pi-download"
                                class="p-button-success mr-2"
                                @click="openModalImport()"
                            />
                        </div>
                    </template>
                </Toolbar>
                asd asd asd sa
            </div>
        </div>
        <ImportarModal ref="refImportarModal" />
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout.vue";
import Button from "primevue/button";
export default {
    name: "Index",
    layout: AppLayout,
};
</script>

<script setup>
import { ref } from "vue";
import ImportarModal from "./Components/ImportarModal.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Toolbar from "primevue/toolbar";

const refImportarModal = ref();
function openModalImport() {
    refImportarModal.value?.open();
}
</script>

<style lang="scss" scoped></style>
