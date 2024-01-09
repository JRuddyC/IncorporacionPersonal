<template>
    <Head title="Register" />
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 mt-5 xl:mt-0 text-center">
                <img :src="'images/logo-' + logoColor + '.svg'" alt="Sakai logo" class="mb-5" style="width:81px; height:60px;">
            </div>
            <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, var(--primary-color), rgba(33, 150, 243, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                    <div class="text-center mb-5">
                        <img src="images/avatar.png" alt="Image" height="50" class="mb-3">
                        <div class="text-900 text-3xl font-medium mb-3">BIENVENIDO!</div>
                        <span class="text-600 font-medium">Registrese para continuar</span>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="w-full md:w-10 mx-auto">
                            <label for="name" class="block text-900 text-xl font-medium mb-2">Nombre de usuario</label>
                            <InputText id="name" v-model="form.name" type="text" class="w-full mb-3" placeholder="Name" style="padding:1rem;" />

                            <label for="email" class="block text-900 text-xl font-medium mb-2">Correo electronico</label>
                            <InputText id="email" v-model="form.email" type="text" class="w-full mb-3" placeholder="Email" style="padding:1rem;" />

                            <label for="password" class="block text-900 font-medium text-xl mb-2">Contraseña</label>
                            <Password id="password" v-model="form.password" placeholder="Password" :toggleMask="true" class="w-full mb-3" inputClass="w-full" inputStyle="padding:1rem"></Password>

                            <label for="password_confirmation" class="block text-900 font-medium text-xl mb-2">Repita la contraseña</label>
                            <Password id="password_confirmation" v-model="form.password_confirmation" placeholder="Password Confirmation" :toggleMask="true" class="w-full mb-3" inputClass="w-full" inputStyle="padding:1rem"></Password>

                            <div class="flex align-items-center justify-content-between mb-5">
                                <div class="flex align-items-center" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                                    <Checkbox id="terms" v-model="form.terms" name="terms" :binary="true" class="mr-2"></Checkbox>
                                    <div class="ml-2">
                                        Estoy de acuerdo con los <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terminos de referencia</a> y <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Politicas de Privacidad</a>
                                    </div>
                                </div>
                                <a :href="route('login')" class="font-medium no-underline ml-2 text-right cursor-pointer" style="color: var(--primary-color)">ya esta registrado?</a>
                            </div>
                            <Button type="submit" label="Registrarse" class="w-full p-3 text-xl" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head,Link } from '@inertiajs/inertia-vue3';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';

export default {
    components: {
        Button,
        Checkbox,
        InputText,
        Password,
        Link,
        Head
    },
    computed: {
        logoColor() {
            return 'dark';
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false
            })
        }
    },
    methods: {
        submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
    }
}
</script>

<style scoped>
.pi-eye {
    transform:scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform:scale(1.6);
    margin-right: 1rem;
}
</style>
