<template>
    <button type="button" :disabled="isLoading" @click="submit">
        <slot v-if="!isLoading"></slot>
        <svg viewBox="0 0 110 110" v-else style="height: 1em;">
            <circle id="loader-circle" cx="55" cy="55" r="50" stroke-dasharray="314.16" stroke="currentColor" fill="none" stroke-width="10"></circle>	
            <animate 
                xlink:href="#loader-circle"
                attributeName="stroke-dashoffset"
                attributeType="XML"
                from="314.16"
                to="314.16" 
                dur="3s"
                begin="0s"
                keyTimes="0; 0.25; 0.5; 0.75; 1;"
                values="314.16; 60; -314.16; 60; 314.16;"
                repeatCount="indefinite"/>
            <animateTransform 
                xlink:href="#loader-circle"
                attributeName="transform" 
                attributeType="XML"
                type="rotate"
                from="0 55 55"
                to="360 55 55" 
                dur="3s"
                repeatCount="indefinite"
                />
        </svg>	
    </button>
</template>

<script>
export default {
    props: {
        action: {required: true},
        method: {default: 'GET'},
        data: {default: () => ({})},
    },
    data() {
        return {
            isLoading: false
        }
    },
    methods: {
        submit(evt) {
            this.isLoading = true;
            evt.preventDefault();

            const options = this.method.toLowerCase() === 'get' ? 
                {params: this.data} :
                this.data;

            axios[this.method.toLowerCase()](this.action, options)
                .then(response => {
                    this.$emit('success', response);
                    this.isLoading = false;
                })
                .catch(({response}) => this.$emit('failure', response));
        }
    }
}
</script>