<template>
	<div>
		<label class="control">Question type</label>
		<div class="flex mb-2">
			<div class="px-2">
				MCQ
				<input class type="radio" @click="toggleMcq(true)" name="type" checked />
			</div>
			<div class="px-2">
				Input
				<input class type="radio" @click="toggleMcq(false)" name="type" />
			</div>
		</div>

		<div v-if="mcq">
			<label class="control">Options</label>
			<div class="flex">
				<div class="w-full">
					<input
						v-for="i in count"
						:key="i"
						class="control"
						name="options[]"
						:placeholder="'Enter option ' + i"
						type="text"
						required
					/>
				</div>
				<div>
					<button type="button" @click="addOption" class="btn is-blue text-white mb-2 ml-2">+</button>
				</div>
				<div v-if="count > 1">
					<button type="button" @click="removeOption" class="btn is-red text-white mb-2 ml-2">-</button>
				</div>
			</div>
		</div>

		<div v-if="!mcq">
			<label class="control">Correct answer keys</label>
			<input
				class="control"
				type="text"
				name="correct_answer_keys"
				placeholder="if multiple, seperate it with |"
				required
			/>
		</div>
	</div>
</template>

<script>
import MarkdownEditor from "./MarkdownEditor";
export default {
	data() {
		return {
			mcq: true,
			count: 4
		};
	},
	methods: {
		addOption() {
			this.count++;
		},
		toggleMcq(b) {
			this.mcq = b;
		},
		removeOption() {
			this.count--;
		}
	}
};
</script>