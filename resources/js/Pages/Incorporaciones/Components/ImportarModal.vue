<template>
    <Dialog class="modal-import" v-model:visible="show" header="MIGRAR EXCEL" content-class="content-dialog-import">
        <template #default>
            <FileUpload choose-label="Seleccionar" upload-label="Subir" cancel-label="Cancelar" name="demo[]" url="/api/upload" @upload="onAdvancedUpload($event)" :multiple="false" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" :maxFileSize="1000000">
                <template #empty>
                    <p>Seleccione un archivo o arrastre el archivo Excel aqui.</p>
                </template>
            </FileUpload>
        </template>
    </Dialog>
</template>

<script setup>
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import { ref } from 'vue';

const show = ref(false);
function open() {
    show.value = true;
}
defineExpose({
    open,
})
</script>

<style lang="scss" >
.modal-import.p-dialog .p-dialog-content {
    width: 400px;
}
</style>
