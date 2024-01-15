<template>
    <Dialog
        class="modal-import"
        v-model:visible="show"
        header="Migrar Imagenes"
        content-class="content-dialog-import"
    >
        <template #default>
            <FileUpload
                choose-label="Seleccionar"
                upload-label="Subir"
                cancel-label="Cancelar"
                name="file"
                url="/api/importar-imagenes"
                @before-send="beforeUpload"
                :withCredentials="true"
                @upload="onAdvancedUpload($event)"
                :multiple="false"
                :maxFileSize="1000000"
            >
                <template #empty>
                    <p>
                        Seleccione un archivo o arrastre el archivo Excel aqui.
                    </p>
                </template>
            </FileUpload>
        </template>
    </Dialog>
</template>

<script setup>
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import { ref, computed } from "vue";
import Swal from 'sweetalert2/dist/sweetalert2.js'

const show = ref(false);
function open() {
    show.value = true;
}
defineExpose({
    open,
});

const csrf = computed(() =>
    document.head.querySelector('meta[name="csrf-token"]')
        ? document.head.querySelector('meta[name="csrf-token"]').content
        : ""
);
function beforeUpload(request) {
    console.log(request);
    request.xhr.setRequestHeader("X-CSRF-TOKEN", csrf.value);
    request.xhr.timeout = 1000*60*2;
    request.xhr.onload = (resp) => {
        const respServer = JSON.parse(resp.originalTarget?.response);
        Swal.fire({
            title: respServer.success?'Exito!':'Error!',
            text: respServer.success?'Imagenes migrados exitosamente!':respServer.message,
            icon: respServer.success?'success':'error',
            confirmButtonText: 'Aceptar'
        });
    };
    return request;
}
function onAdvancedUpload(ev){
}
</script>

<style lang="scss">
.modal-import.p-dialog .p-dialog-content {
    width: 400px;
}
</style>
