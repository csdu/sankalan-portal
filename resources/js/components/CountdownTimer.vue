<template>
    <div>
        <slot :timer="timer" :format="format">
            <span v-if="days" >{{ format(days, 2)}}:</span>
            <span v-if="hours" >{{ format(hours, 2)}}:</span>
            <span>{{ format(minutes, 2) }}:</span>
            <span>{{ format(seconds, 2) }}.</span>
            <span class="text-sm">{{ format(milliseconds, 3) }}</span>
        </slot>
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
            intervalTimer: null,
            ended: false,
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
            return Math.floor(this.timeLeft / 1000 / 60 / 60) % 24;
        },
        days() {
            return Math.floor(this.timeLeft / 1000 / 60 / 60 / 24);
        },
        timer() {
            return {
                milliseconds: this.milliseconds,
                seconds: this.seconds,
                minutes: this.minutes,
                hours: this.hours,
                days: this.days,
            }
        }
    },
    methods: {
        refreshTime() {
            this.currentTime = new Date().getTime();
            if(this.timeLeft <= this.framesDuration) {
                this.currentTime += this.timeLeft; // to make timer goto zero
                clearInterval(this.intervalTimer);
                this.$emit('timeup');
                this.ended = true;
            }
            if(this.timeLeft <= this.hurry * 1000) {
                this.$emit('hurryup');
            }
        },
        format(number, minDigits) {
            const digits = number.toString().length;
            if(digits >= minDigits) {
               return number;
            }
            return (new Array(minDigits-digits)).fill(0).join("").concat(number)
        }
    },
    mounted() {
        this.intervalTimer = setInterval(this.refreshTime, this.framesDuration);
    }
}
</script>
