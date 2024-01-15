<template>
    <Head title="Planilla"></Head>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toolbar class="mb-4">
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
                asd asd asd sa
            </div>
        </div>
        <ImportarModal ref="refImportarModal" />
        <ImportarFotos ref="refImportarFotos" />
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
import ImportarFotos from "./Components/ImportarFotos.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Toolbar from "primevue/toolbar";

const refImportarModal = ref();

const refImportarFotos = ref();

function openModalImport() {
    refImportarModal.value?.open();
}
function openModalImportFotos() {
    refImportarFotos.value?.open();
}
</script>

<style lang="scss" scoped></style>
