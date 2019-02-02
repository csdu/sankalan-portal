<template>
    <tr>
       <slot :event="event" :onComplete="onComplete"></slot> 
    </tr>
</template>
<script>
export default {
    props: ['dataEvent'],
    data() {
        return {
            event: this.dataEvent
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
