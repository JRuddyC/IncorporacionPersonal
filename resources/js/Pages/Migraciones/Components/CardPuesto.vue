<template>
    <div class="card-container">
        <article
            :class="{
                'material-card': true,
                'Blue-Grey': esAcefalia,
                Indigo: !esAcefalia,
                'mc-active': isActive,
            }"
        >
            <h2>
                <span class="mb-2">
                    <i class="pi pi-briefcase"></i>
                    {{ puesto.denominacion }}
                    <b class="span-item" title="ITEM">{{ puesto.item }}</b>
                </span>
                <div
                    class="flex flex-wrap flex-row-reverse justify-content-between w-full"
                >
                    <b
                        class="span-estado"
                        :class="{
                            'span-estado-red': esAcefalia,
                            'span-estado-green': !esAcefalia,
                        }"
                        title="ESTADO"
                        >{{ puesto.estado }}</b
                    >
                    <strong v-if="!esAcefalia">
                        <i class="pi pi-user"></i>
                        {{ puesto.nombre_completo }}
                    </strong>
                </div>
            </h2>
            <div class="mc-content">
                <div class="img-container">
                    <img class="img-responsive" v-if="puesto.imagen" :src="'/imagen-persona/' + puesto.persona_actual_id" >
                    <img class="img-responsive" v-else-if="!puesto.persona_actual_id" src="/img/team-3.jpg" >
                    <img class="img-responsive" v-else src="/img/team-2.jpg">
                    <!-- <img
                        class="img-responsive"
                        src="https://wpdmcdn.s3.amazonaws.com/me.jpg"
                    /> -->
                </div>
                <div v-if="descPos === 1" class="mc-description">
                    Información de la persona
                </div>
                <div v-if="descPos === 2" class="mc-description">
                    información del puesto
                </div>
                <div v-if="descPos === 3" class="mc-description">
                    información de requisitos del puesto
                </div>
            </div>
            <a class="mc-btn-action" @click="onClickCard">
                <i
                    :class="{
                        pi: true,
                        'pi-bars': !isActive,
                        'pi-spin': animation,
                        'pi-arrow-left': isActive,
                    }"
                ></i>
            </a>
            <div class="mc-footer">
                <h4>Opciones</h4>
                <Button
                    v-if="!esAcefalia"
                    :disabled="descPos === 1"
                    class="p p-button-lg mr-3"
                    icon="pi pi-user-plus"
                    severity="secondary"
                    text
                    raised
                    rounded
                    @click="()=>descPos = 1"
                />
                <Button
                    :disabled="descPos === 2"
                    class="p p-button-lg mr-3"
                    icon="pi pi-briefcase"
                    severity="secondary"
                    text
                    raised
                    rounded
                    @click="()=>descPos = 2"
                />
                <Button
                    :disabled="descPos === 3"
                    class="p p-button-lg"
                    icon="pi pi-flag-fill"
                    severity="secondary"
                    text
                    raised
                    rounded
                    @click="()=>descPos = 3"
                />
            </div>
        </article>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Button from "primevue/button";
import { computed } from "vue";
import Tag from "primevue/tag";
import axios from "axios";

const props = defineProps({
    puesto: {
        type: Object,
        required: true,
    },
});

const esAcefalia = computed(() => !props.puesto.persona_actual_id);

const descPos = ref(esAcefalia.value? 2 : 1 );
// 1: persInfo, 2:puesto desc , 3:requerimientos
const isActive = ref(false);
const animation = ref(false);
function onClickCard() {
    animation.value = true;
    getDetalleReg();
    setTimeout(function () {
        isActive.value = !isActive.value;
        animation.value = false;
    }, 800);
}

const loading = ref(false);
const detallePuesto = ref();
const requisitos = computed(() => {
return detallePuesto.value?.requisitos?.length ? detallePuesto.value?.requisitos : {};
});
function getDetalleReg() {
    if (!detallePuesto.value?.id && !loading.value) {
        loading.value = true;
        axios
        .get(`/api/persona-puesto/${props.puesto.id}`)
        .then(function (response) {
            if (response.data) {
            detallePuesto.value = response.data;
            }
            loading.value = false;
        })
        .catch(function (error) {
            loading.value = false;
            console.log('error:', error.data)
        });
    }
}
</script>

<style lang="scss" scoped>
.span-item {
    background-color: var(--surface-card);
    border-radius: var(--border-radius);
    color: var(--text-color);
    font-weight: bold;
    font-size: 0.7em;
    padding: 4px;
    padding-top: 2px;
    padding-bottom: 0px;
    float: right;
    margin-top: -2px;
}

.span-estado {
    text-transform: uppercase;
    border-radius: var(--border-radius);
    font-weight: bold;
    font-size: 0.7em;
    padding: 4px;
    padding-top: 2px;
    padding-bottom: 0px;
    float: right;
    margin-top: -2px;
}

.span-estado-red {
    background-color: var(--red-500);
}
.span-estado-green {
    background-color: var(--green-500);
}
.pi-spin {
    -webkit-animation: pi-spin 0.2s infinite linear;
    animation: pi-spin 0.2s infinite linear;
}
@-webkit-keyframes pi-spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}
@keyframes pi-spin {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }
}

.card-container {
    margin: 5px;
    height: 380px;
    width: 330px;
}
.material-card {
    position: relative;
    padding-bottom: calc(100% - 16px);
    margin-bottom: 6.6em;
}
.material-card h2 {
    position: absolute;
    top: calc(100% - 16px);
    left: 0;
    width: 100%;
    height: 82px;
    padding: 10px 16px;
    color: #fff;
    font-size: 1em;
    line-height: 1.6em;
    margin: 0;
    z-index: 10;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card h2 span {
    display: block;
}
.material-card h2 strong {
    font-weight: 400;
    display: block;
    font-size: 0.8em;
}
.material-card h2:before,
.material-card h2:after {
    content: " ";
    position: absolute;
    left: 0;
    top: -16px;
    width: 0;
    border: 8px solid;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card h2:after {
    top: auto;
    bottom: 0;
}
@media screen and (max-width: 767px) {
    .material-card.mc-active {
        padding-bottom: 0;
        height: auto;
    }
}
.material-card.mc-active h2 {
    top: 0;
    padding: 10px 16px 10px 90px;
}
.material-card.mc-active h2:before {
    top: 0;
}
.material-card.mc-active h2:after {
    bottom: -16px;
}
.material-card .mc-content {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 16px;
    left: 16px;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card .mc-btn-action {
    position: absolute;
    right: 16px;
    top: 15px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    border: 5px solid;
    width: 54px;
    height: 54px;
    line-height: 44px;
    text-align: center;
    color: #fff !important;
    cursor: pointer;
    z-index: 20;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card.mc-active .mc-btn-action {
    top: 62px;
}
.material-card .mc-description {
    position: absolute;
    top: 100%;
    right: 30px;
    left: 30px;
    bottom: 54px;
    overflow: hidden;
    opacity: 0;
    filter: alpha(opacity=0);
    -webkit-transition: all 1.2s;
    -moz-transition: all 1.2s;
    -ms-transition: all 1.2s;
    -o-transition: all 1.2s;
    transition: all 1.2s;
}
.material-card .mc-footer {
    height: 0;
    overflow: hidden;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card .mc-footer h4 {
    position: absolute;
    top: 200px;
    left: 30px;
    padding: 0;
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    -webkit-transition: all 1.4s;
    -moz-transition: all 1.4s;
    -ms-transition: all 1.4s;
    -o-transition: all 1.4s;
    transition: all 1.4s;
}
.material-card .mc-footer a:nth-child(1) {
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    -ms-transition: all 0.5s;
    -o-transition: all 0.5s;
    transition: all 0.5s;
}
.material-card .mc-footer a:nth-child(2) {
    -webkit-transition: all 0.6s;
    -moz-transition: all 0.6s;
    -ms-transition: all 0.6s;
    -o-transition: all 0.6s;
    transition: all 0.6s;
}
.material-card .mc-footer a:nth-child(3) {
    -webkit-transition: all 0.7s;
    -moz-transition: all 0.7s;
    -ms-transition: all 0.7s;
    -o-transition: all 0.7s;
    transition: all 0.7s;
}
.material-card .mc-footer a:nth-child(4) {
    -webkit-transition: all 0.8s;
    -moz-transition: all 0.8s;
    -ms-transition: all 0.8s;
    -o-transition: all 0.8s;
    transition: all 0.8s;
}
.material-card .mc-footer a:nth-child(5) {
    -webkit-transition: all 0.9s;
    -moz-transition: all 0.9s;
    -ms-transition: all 0.9s;
    -o-transition: all 0.9s;
    transition: all 0.9s;
}
.material-card .img-container {
    overflow: hidden;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 3;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -ms-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}
.material-card.mc-active .img-container {
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    left: 0;
    top: 12px;
    width: 60px;
    height: 60px;
    z-index: 20;
}
.img-responsive {
    width: 100%;
    // aspect-ratio: 1/1;
    height: 100%;
    object-fit: cover;
}
.material-card.mc-active .mc-content {
    padding-top: 5.6em;
}
@media screen and (max-width: 767px) {
    .material-card.mc-active .mc-content {
        position: relative;
        margin-right: 16px;
    }
}
.material-card.mc-active .mc-description {
    top: 50px;
    padding-top: 5.6em;
    opacity: 1;
    filter: alpha(opacity=100);
}
@media screen and (max-width: 767px) {
    .material-card.mc-active .mc-description {
        position: relative;
        top: auto;
        right: auto;
        left: auto;
        padding: 50px 30px 70px 30px;
        bottom: 0;
    }
}
.material-card.mc-active .mc-footer {
    overflow: visible;
    position: absolute;
    top: calc(100% - 16px);
    left: 16px;
    right: 0;
    height: 82px;
    padding-top: 15px;
    padding-left: 25px;
}
.material-card.mc-active .mc-footer h4 {
    top: -32px;
}

.material-card.Indigo h2 {
    background-color: #3f51b5;
}
.material-card.Indigo h2:after {
    border-top-color: #3f51b5;
    border-right-color: #3f51b5;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Indigo h2:before {
    border-top-color: transparent;
    border-right-color: #1a237e;
    border-bottom-color: #1a237e;
    border-left-color: transparent;
}
.material-card.Indigo.mc-active h2:before {
    border-top-color: transparent;
    border-right-color: #3f51b5;
    border-bottom-color: #3f51b5;
    border-left-color: transparent;
}
.material-card.Indigo.mc-active h2:after {
    border-top-color: #1a237e;
    border-right-color: #1a237e;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Indigo .mc-btn-action {
    background-color: #3f51b5;
}
.material-card.Indigo .mc-btn-action:hover {
    background-color: #1a237e;
}
.material-card.Indigo .mc-footer h4 {
    color: #1a237e;
}
.material-card.Indigo .mc-footer a {
    background-color: #1a237e;
}
.material-card.Indigo.mc-active .mc-content {
    background-color: #e8eaf6;
}
.material-card.Indigo.mc-active .mc-footer {
    background-color: #c5cae9;
}
.material-card.Indigo.mc-active .mc-btn-action {
    border-color: #e8eaf6;
}

.material-card.Blue-Grey h2 {
    background-color: #607d8b;
}
.material-card.Blue-Grey h2:after {
    border-top-color: #607d8b;
    border-right-color: #607d8b;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Blue-Grey h2:before {
    border-top-color: transparent;
    border-right-color: #263238;
    border-bottom-color: #263238;
    border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:before {
    border-top-color: transparent;
    border-right-color: #607d8b;
    border-bottom-color: #607d8b;
    border-left-color: transparent;
}
.material-card.Blue-Grey.mc-active h2:after {
    border-top-color: #263238;
    border-right-color: #263238;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Blue-Grey .mc-btn-action {
    background-color: #607d8b;
}
.material-card.Blue-Grey .mc-btn-action:hover {
    background-color: #263238;
}
.material-card.Blue-Grey .mc-footer h4 {
    color: #263238;
}
.material-card.Blue-Grey .mc-footer a {
    background-color: #263238;
}
.material-card.Blue-Grey.mc-active .mc-content {
    background-color: #eceff1;
}
.material-card.Blue-Grey.mc-active .mc-footer {
    background-color: #cfd8dc;
}
.material-card.Blue-Grey.mc-active .mc-btn-action {
    border-color: #eceff1;
}
body {
    background-color: #eceff1;
    color: #37474f;
    font-family: "Raleway", sans-serif;
    font-weight: 300;
    font-size: 16px;
}
h1,
h2,
h3 {
    font-weight: 200;
}
</style>
