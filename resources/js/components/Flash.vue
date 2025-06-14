<template>
    <div class="fixed flex flex-col inset-x-0 bottom-24 p-4 items-center z-50 pointer-events-none">
        <transition-group name="fade"
        enter-active-class="fadeIn"
        leave-active-class="fadeOut"
        mode="in-out">
            <div class="border rounded mb-2 p-3 max-w-5/6 md:max-w-1/3"
                :class="classes[message.level]"
                v-for="message in messages" :key="message.id">
                {{ message.message }}
            </div>
        </transition-group>
    </div>
</template>

<script>
export default {
    props: {
        'data-messages':{
            default: []
        }
    },
    data() {
        return {
            messages : [],
            classes: {
                success: 'bg-emerald-100 border-emerald-700 text-emerald-700',
                danger: 'bg-red-100 border-red-700 text-red-700',
                info: 'bg-blue-100 border-blue-700 text-blue-700',
                warning: 'bg-amber-100 border-amber-800 text-amber-800',
            }
        }
    },
    methods: {
        clear() {
            for(let i=0; i< this.messages.length; i++){
                setTimeout(() => {
                    this.messages.splice(0, 1);
                }, i*500);
            }
        },
        flash(message) {
            this.messages.push(message);
            setTimeout(() => this.clear(), 5000);
        },
        schema() {
            return ['id', 'message', 'level', 'important'];
        }
    },
    mounted() {
        this.dataMessages.forEach(message => this.messages.push(message));
        setTimeout(() => this.clear(), 5000);

        console.log(this.$eventBus, Object.keys(this.$eventBus));

        this.$eventBus.on('flash', message => {
            this.flash(message)
        });
    }
}
</script>
<style scoped>
    .fadeIn, .fadeOut {
        animation: fade;
        animation-duration: 0.5s;
    }
    .fadeOut {
        animation-direction: reverse;
    }
    @keyframes fade {
        0% {
            transform: translateY(200%);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>


