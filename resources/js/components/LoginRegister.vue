<template>
    <slot :isRegister="isRegister" :login="login" :register="register"></slot>
</template>
<script>
export default {
    data() {
        return {
            isRegister: false
        }
    },
    methods: {
        login() {
            this.isRegister = false;
            window.location.hash = 'login';
        },
        register() {
            this.isRegister = true;
            window.location.hash = 'register';
        }
    },
    mounted() {
        if(window.location.hash == '#login') {
            this.isRegister = false;
        }
    }
}
</script>
