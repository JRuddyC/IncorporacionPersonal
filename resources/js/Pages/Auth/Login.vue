<template>
  <Head title="Login"/>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <transition-group name="p-message" tag="div" v-if="form.errors">
          <Message v-for="(error, key) of form.errors" severity="error" :key="key">{{ error }}</Message>
        </transition-group>
        <form action="#" class="sign-in-form">
        <h2 class="title">Iniciar sesión</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <InputText id="email1" v-model="form.email" type="text" class="w-full mb-3 input-login" placeholder="Ingresar su correo" style="padding:1rem;" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <Password id="password1" v-model="form.password" placeholder="Ingrese su correo" :toggleMask="true" class="w-full mb-3 input-login" inputClass="w-full" inputStyle="padding:1rem" @keyup.enter="submit"></Password>
          </div>
          <div class="button-container">
              <input type="submit" @click="submit"  value="ENTRAR" class="btn solid" :loading="form.processing"/>
              <a :href="route('register')" class="btn transparent" id="sign-up-btn">REGISTRARSE</a>
          </div>
        </form>
      </div>
    </div>
    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>¡BIENVENIDO!</h3>
          <p>Disfruta de todas las funcionalidades que nuestro sistema tiene para ofrecer.</p>
        </div>
        <img src="images/image.svg" class="image" alt=""/>
      </div>
    </div>
  </div>
</template>
<script>
import { Head } from '@inertiajs/inertia-vue3';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Message from 'primevue/message';

export default {
    components: {
        Button,
        Checkbox,
        InputText,
        Password,
        Message,
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
                email: '',
                password: '',
                remember: false
            })
        }
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ... data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
            console.log(this.form)
        }
    }
}
</script>
<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}

input {
    background: none;
    outline: none;
    border: none;
    line-height: 1;
    font-size: 1.1rem;
}
input::placeholder {
    color: #aaa;
}

input:focus { 
  box-shadow: none;
}

::v-deep .input-login {
    input {
        background: none;
        outline: none;
        border: none;
        line-height: 1;
        font-size: 1.1rem;
    }
    input::placeholder {
        color: #aaa;
    }
    input:focus {
      box-shadow: none;
    }
}

.container {
position: relative;
width: 100%;
background-color: #fff;
min-height: 100vh;
overflow: hidden;
}

.forms-container {
position: absolute;
width: 100%;
height: 100%;
top: 0;
left: 0;
}

.signin-signup {
position: absolute;
top: 50%;
transform: translate(-50%, -50%);
left: 75%;
width: 50%;
transition: 1s 0.7s ease-in-out;
display: grid;
grid-template-columns: 1fr;
z-index: 5;
}

form {
display: flex;
align-items: center;
justify-content: center;
flex-direction: column;
padding: 0rem 5rem;
transition: all 0.2s 0.7s;
overflow: hidden;
grid-column: 1 / 2;
grid-row: 1 / 2;
}

form.sign-in-form {
z-index: 0;
}

.title {
font-size: 2.2rem;
color: #444;
margin-bottom: 10px;
font-weight: 600;
}

.input-field {
max-width: 380px;
width: 100%;
background-color: #f0f0f0;
margin: 10px 0;
height: 55px;
border-radius: 55px;
display: grid;
grid-template-columns: 15% 85%;
padding: 0 0.4rem;
position: relative;
}

.input-field i {
text-align: center;
line-height: 55px;
color: #acacac;
transition: 0.5s;
font-size: 1.1rem;
}

.button-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px; /* Ajusta según sea necesario */
}

.btn {
width: 150px;
background-color: #172554;
border: none;
outline: none;
height: 49px;
border-radius: 49px;
color: #fff;
text-transform: uppercase;
font-weight: 600;
margin: 10px 0;
cursor: pointer;
transition: 0.5s;
margin-right: 20px;
}

.btn:hover {
background-color: #172554;
}
.panels-container {
position: absolute;
height: 100%;
width: 100%;
top: 0;
left: 0;
display: grid;
grid-template-columns: repeat(2, 1fr);
}

.container:before {
content: "";
position: absolute;
height: 2000px;
width: 2000px;
top: -10%;
right: 48%;
transform: translateY(-50%);
background-image: linear-gradient(-45deg, #172554 0%, #172554 100%);
transition: 1.8s ease-in-out;
border-radius: 50%;
z-index: 6;
}

.image {
width: 80%;
transition: transform 1.1s ease-in-out;
transition-delay: 0.4s;
}

.panel {
display: flex;
flex-direction: column;
align-items: flex-end;
justify-content: space-around;
text-align: center;
z-index: 6;
}

.left-panel {
pointer-events: all;
padding: 3rem 17% 2rem 12%;
}

.panel .content {
color: #fff;
transition: transform 0.9s ease-in-out;
transition-delay: 0.6s;
}

.panel h3 {
font-weight: 600;
line-height: 1;
font-size: 1.5rem;
}

.panel p {
font-size: 0.95rem;
padding: 0.7rem 0;
}

.btn.transparent {
margin: 0;
background: none;
border: 2px solid #172554;
width: 150px;
height: 49px;
font-weight: 600;
font-size: 0.8rem;
color: #172554;
text-align: center;
line-height: 48px;
}
/* ANIMATION */

.container.sign-up-mode:before {
  transform: translate(100%, -50%);
  right: 52%;
}

.container.sign-up-mode .left-panel .image,
.container.sign-up-mode .left-panel .content {
  transform: translateX(-800px);
}

.container.sign-up-mode .signin-signup {
  left: 25%;
}

.container.sign-up-mode form.sign-up-form {
  opacity: 1;
  z-index: 2;
}

.container.sign-up-mode form.sign-in-form {
  opacity: 0;
  z-index: 1;
}

.container.sign-up-mode .right-panel .image,
.container.sign-up-mode .right-panel .content {
  transform: translateX(0%);
}

.container.sign-up-mode .left-panel {
  pointer-events: none;
}

.container.sign-up-mode .right-panel {
  pointer-events: all;
}

@media (max-width: 870px) {
  .container {
    min-height: 800px;
    height: 100vh;
  }
  .signin-signup {
    width: 100%;
    top: 95%;
    transform: translate(-50%, -100%);
    transition: 1s 0.8s ease-in-out;
  }

  .signin-signup,
  .container.sign-up-mode .signin-signup {
    left: 50%;
  }

  .panels-container {
    grid-template-columns: 1fr;
    grid-template-rows: 1fr 2fr 1fr;
  }

  .panel {
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    padding: 2.5rem 8%;
    grid-column: 1 / 2;
  }

  .right-panel {
    grid-row: 3 / 4;
  }

  .left-panel {
    grid-row: 1 / 2;
  }

  .image {
    width: 200px;
    transition: transform 0.9s ease-in-out;
    transition-delay: 0.6s;
  }

  .panel .content {
    padding-right: 15%;
    transition: transform 0.9s ease-in-out;
    transition-delay: 0.8s;
  }

  .panel h3 {
    font-size: 1.2rem;
  }

  .panel p {
    font-size: 0.7rem;
    padding: 0.5rem 0;
  }

  .btn.transparent {
    width: 150px;
    height: 49px;
    font-size: 0.7rem;
  }

  .container:before {
    width: 1500px;
    height: 1500px;
    transform: translateX(-50%);
    left: 30%;
    bottom: 68%;
    right: initial;
    top: initial;
    transition: 2s ease-in-out;
  }

  .container.sign-up-mode:before {
    transform: translate(-50%, 100%);
    bottom: 32%;
    right: initial;
  }

  .container.sign-up-mode .left-panel .image,
  .container.sign-up-mode .left-panel .content {
    transform: translateY(-300px);
  }

  .container.sign-up-mode .right-panel .image,
  .container.sign-up-mode .right-panel .content {
    transform: translateY(0px);
  }

  .right-panel .image,
  .right-panel .content {
    transform: translateY(300px);
  }

  .container.sign-up-mode .signin-signup {
    top: 5%;
    transform: translate(-50%, 0);
  }
}

@media (max-width: 570px) {
  form {
    padding: 0 1.5rem;
  }

  .image {
    display: none;
  }
  .panel .content {
    padding: 0.5rem 1rem;
  }
  .container {
    padding: 1.5rem;
  }

  .container:before {
    bottom: 72%;
    left: 50%;
  }

  .container.sign-up-mode:before {
    bottom: 28%;
    left: 50%;
  }
}
</style>
