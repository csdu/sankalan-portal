<template>
    <form>
        <select :class="selectClasses" v-model="currentParameter">
            <slot></slot>
        </select>
        <slot name="button" :link="currentRoute"></slot>
    </form>
</template>
<script>
export default {
    props: {
        routeName: {required:true},
        value: {default:''},
        selectClasses: {default:''}
    },
    data() {
        return {
            currentParameter: this.value,
        }
    },
    computed: {
        currentRoute() {
            return route(this.routeName, this.currentParameter);
        }
    }
}
</script>

