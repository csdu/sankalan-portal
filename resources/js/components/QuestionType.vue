<template>
	<div>
		<label class="control">Question type</label>
		<div class="flex mb-2">
			<div class="px-2">
				MCQ
				<input class type="radio" @click="toggleMcq(true)" value="mcq" name="type" checked />
			</div>
			<div class="px-2">
				Input
				<input class type="radio" @click="toggleMcq(false)" value="input" name="type" />
			</div>
		</div>

		<div v-if="mcq">
			<label class="control">Options</label>
			<div>
				<div class="w-full flex items-center" v-for="i in count" :key="i">
					<input
						class="control mr-4"
						:name="`options[${i-1}]`"
						:placeholder="'Enter option ' + i"
						type="text"
						required
					/>
					<label :for="`option-${i-1}`" class="inline-flex items-center">
						<input
							type="radio"
							name="correct_answer_keys"
							:id="`option-${i-1}`"
							:value="i-1"
							class="mr-1"
							required
						/>
						Correct
					</label>
				</div>
				<div>
					<button type="button" @click="addOption" class="btn is-blue text-white mb-2 ml-2">+</button>
					<div v-if="count > 1" class="inline-flex">
						<button type="button" @click="removeOption" class="btn is-red text-white mb-2 ml-2">-</button>
					</div>
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
export default {
	props: {
		isMcq: { default: true },
		counter: { default: 2 }
	},
	data() {
		return {
			mcq: this.isMcq,
			count: this.counter
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