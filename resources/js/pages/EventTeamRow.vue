<template>
    <tr>
       <slot :team="eventTeam.team" :event="eventTeam.event" :onComplete="onComplete"></slot> 
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
            if(data.hasOwnProperty('event')) {
                this.event.isLive = data.event.isLive;
                this.event.hasEnded = data.event.hasEnded;
            }
            flash(data.message, data.status);
        }
    }
}
</script>
