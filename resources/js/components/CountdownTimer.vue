<template>
    <div>
        <span v-if="timeLeft.hours" >{{timeLeft.hours | format(2)}}:</span>
        <span>{{ timeLeft.mins | format(2) }}:</span>
        <span>{{ timeLeft.secs | format(2) }}.</span>
        <span class="text-sm">{{ timeLeft.millis | format(3) }}</span>
    </div>
</template>
<script>
export default {
    props: {
        duration: {required: true},
        hurry: {default: 15}
    },
    data() {
        return {
            timer: null,
            framesDuration: 1000/25,   //25 frames per second
            timeLimit: this.duration * 1000 // seconds to millis
        }
    },
    methods: {
        count() {
            this.timeLimit -= this.framesDuration;
            if(this.timeLimit <= 0) {
                clearInterval(this.timer);
                this.$emit('timeup');
            }
            if(this.timeLimit <= this.hurry * 1000) {
                this.$emit('hurryup');
            }
        }
    },
    filters: {
        format(number, minDigits) {
            const digits = number.toString().length;
            if(digits >= minDigits) {
               return number;
            }
            return (new Array(minDigits-digits)).fill(0).join("").concat(number)
        }
    },
    computed: {
        timeLeft() {
            const millis = this.timeLimit % 1000;
            const secs = Math.floor(this.timeLimit / 1000) % 60;
            const mins = Math.floor(this.timeLimit / 1000 / 60) % 60;
            const hours = Math.floor(this.timeLimit / 1000 / 60 / 60);
            return {hours, mins, secs, millis};
        }
    },
    mounted() {
        this.timer = setInterval(this.count, this.framesDuration);
    }
}
</script>
