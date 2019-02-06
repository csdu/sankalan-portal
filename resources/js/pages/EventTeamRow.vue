<template>
    <tr>
       <slot :team="eventTeam.team" 
       :event="eventTeam.event" 
       :allowed="eventTeam.isActiveQuizAllowed" 
       :onComplete="onComplete"></slot> 
    </tr>
</template>
<script>
export default {
    props: ['dataEventTeam'],
    data() {
        return {
            eventTeam: this.dataEventTeam,
        }
    },
    methods: {
        onComplete({data}) {
            if(data.status == 'success') {
                this.eventTeam.isActiveQuizAllowed = true;
            }
            flash(data.message, data.status);
        }
    }
}
</script>
