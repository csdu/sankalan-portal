export default {
    props: ['dataEvents'],
    data() {
        return {
            events: this.dataEvents,
            routes: {
                'end': '/manage/events/{event}/end',
                'start': '/manage/events/{event}/start'
            }
        }
    },
    methods: {
        route(name, event) {
            return this.routes[name].replace('{event}', event.slug);
        },
        onComplete({data}) {
            if(data.hasOwnProperty('event')) {
                const index = this.events.findIndex(event => event.id == data.event.id)
                let event = this.events[index];
                event.isLive = data.event.isLive;
                event.hasEnded = data.event.hasEnded;
                this.events.splice(index, 1, event);
            }
            flash(data.message, data.status);
        }
    }
}