<template>
    <tr>
       <slot :quiz="quizTeam.quiz"
        :participation-id="quizTeam.id" 
        :team="quizTeam.team" 
        :responses_count="quizTeam.responses_count" 
        :start_time="quizTeam.started_at"
        :finish_time="quizTeam.finished_at"
        :score="quizTeam.score"
        :onComplete="onComplete"></slot> 
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
            if(data.hasOwnProperty('score')) {
                this.quizTeam.score = data.score;
            }
            flash(data.message, data.status);
        }
    }
}
</script>
