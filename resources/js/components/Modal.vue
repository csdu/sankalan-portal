<template>
	<div
		class="absolute top-0 right-0 bottom-0 left-0 flex items-center content-center self-center justify-center bg-black/20"
	>
		<div class="bg-white shadow-2 p-4 rounded">
			<div class="flex justify-between items-center">
				<h3 class="text-xl font-normal">{{ title }}</h3>
				<button
					@click="$emit('close')"
					class="py-2 px-3 rounded-full bg-slate-200 hover:bg-slate-300 leading-none text-base"
					title="close"
				>x</button>
			</div>
			<slot></slot>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		title: {
			default: ""
		}
	},
    emits: ['close']
};
</script>
