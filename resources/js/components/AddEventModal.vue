<script>
import Modal from "./Modal.vue";

export default {
	components: {
		Modal
	},
	data() {
		return {
			show: false
		};
	},
	methods: {
		open() {
			this.show = true;
		},
		close() {
			this.show = false;
		}
	}
};
</script>
