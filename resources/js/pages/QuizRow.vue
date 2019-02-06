<template>
    <tr>
       <slot :quiz="quiz" :onComplete="onComplete" :time-limit="timeLimit"></slot> 
    </tr>
</template>
<script>
export default {
    props: ['dataQuiz'],
    data() {
        return {
            quiz: this.dataQuiz
        }
    },
    computed: {
        timeLimit() {
            return Math.floor(this.quiz.time_limit / 60) + ':' + this.format(this.quiz.time_limit % 60, 2);
        }
    },
    methods: {
        format(number, minDigits) {
            const digits = number.toString().length;
            if(digits >= minDigits) {
               return number;
            }
            return (new Array(minDigits-digits)).fill(0).join("").concat(number)
        },
        onComplete({data}) {
            if(data.hasOwnProperty('quiz')) {
                this.quiz.isActive = data.quiz.isActive;
                this.quiz.isClosed = data.quiz.isClosed;
                this.quiz.event = data.quiz.event;
            }
            flash(data.message, data.status);
        }
    }
}
</script>
