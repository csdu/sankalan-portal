<template>
    <div>
        <span v-if="hours" >{{hours | format(2)}}:</span>
        <span>{{ minutes | format(2) }}:</span>
        <span>{{ seconds | format(2) }}.</span>
        <span class="text-sm">{{ milliseconds | format(3) }}</span>
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
            startTime: new Date().getTime(),
            currentTime: new Date().getTime(),
            framesDuration: 1000/30,
        }
    },
    computed: {
        timeSpent() {
            return this.currentTime - this.startTime;
        },
        timeLeft() {
            return (this.duration * 1000) - this.timeSpent;
        },
        milliseconds() {
            return this.timeLeft % 1000;
        },
        seconds() {
            return Math.floor(this.timeLeft / 1000) % 60;
        },
        minutes() {
            return Math.floor(this.timeLeft / 1000 / 60) % 60;
        },
        hours() {
            return Math.floor(this.timeLeft / 1000 / 60 / 60);
        }
    },
    methods: {
        refreshTime() {
            this.currentTime = new Date().getTime();
            if(this.timeLeft <= this.framesDuration) {
                this.currentTime += this.timeLeft; // to make timer goto zero
                clearInterval(this.timer);
                this.$emit('timeup');
            }
            if(this.timeLeft <= this.hurry * 1000) {
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
    mounted() {
        this.timer = setInterval(this.refreshTime, this.framesDuration);
    }
}
</script>
