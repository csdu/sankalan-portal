<template>
    <tr>
       <slot :quiz="quizTeam.quiz" 
        :team="quizTeam.team" 
        :responses_count="quizTeam.responses_count" 
        :start_time="quizTeam.started_at"
        :finish_time="quizTeam.finished_at"
        :onComplete="onComplete" 
        :time-limit="timeLimit"></slot> 
    </tr>
</template>
<script>
export default {
    props: ['dataQuizTeam'],
    data() {
        return {
            quizTeam: this.dataQuizTeam,
        }
    },
    methods: {
        onComplete({data}) {
            flash(data.message, data.status);
        }
    }
}
</script>
