<template>
	<div class="question outline-none">
		<div class="card px-3 pt-3 pb-6 relative overflow-hidden flex">
			<strong class="float-left mr-2" v-text="`Q${dataQuestion.qno}.`"></strong>
			<article class="markdown-body w-full" v-html="dataQuestion.text"></article>
		</div>
		<pre
			v-if="dataQuestion.code"
			class="card p-4 font-mono my-4 leading-normal tracking-wide whitespace-pre-wrap max-h-64 overflow-y-auto"
			v-text="escapedCode"
		></pre>
		<div
			class="flex justify-center my-4"
			v-for="questionAttachment in dataQuestionAttachments"
			:key="questionAttachment.id"
		>
			<img :src="'/question_attachments/' +questionAttachment.id" class="max-w-full rounded shadow-lg" />
		</div>
		<ul class="choices-list list-reset my-8 flex flex-wrap -mx-2" v-if="choicesCount">
			<li
				v-for="(choice, choiceIndex) in dataQuestion.choices"
				:key="choice.id"
				class="mb-3 w-full md:w-1/2 px-2"
			>
				<label
					:for="`choice-${choice.key}`"
					class="relative flex items-center btn hover:bg-grey-light border shadow cursor-pointer pl-6 h-full"
					@mouseover="highlightOption(choiceIndex)"
					:class="{
                        'bg-white': !isHighlighted(choiceIndex) && !isSelected(choiceIndex),
                        'bg-green-dark': isHighlighted(choiceIndex) && isSelected(choiceIndex),
                        'bg-green': !isHighlighted(choiceIndex) && isSelected(choiceIndex),
                        'bg-grey-light border border-grey-darker': isHighlighted(choiceIndex),
                        'text-white hover:bg-green-dark border-green-dark': isSelected(choiceIndex),
                    }"
				>
					<div v-if="isHighlighted(choiceIndex)" class="absolute -ml-3 inset-y-0 left-0 flex items-center">
						<span
							class="inline-block w-2 h-2 rounded-full"
							:class="isSelected(choiceIndex) ? 'bg-white' : 'bg-green'"
						></span>
					</div>
					<input
						:id="`choice-${choice.key}`"
						type="radio"
						class="hidden"
						:name="`question-${choice.question_id}`"
						@click="toggleOption(choiceIndex)"
						:value="choice.key"
					/>
					<div>
						<img
							v-if="choice.illustration"
							:src="choice.illustration"
							:alt="choice.text"
							class="rounded my-2 max-w-full"
						/>
						<pre v-if="choice.code" v-html="choice.code" class="my-2"></pre>
						<p class="ml-1" v-html="choice.text"></p>
					</div>
				</label>
			</li>
		</ul>
		<!-- Input Answer -->
		<div class="card my-4 p-4" v-else>
			<div class="flex" v-if="editing">
				<input
					ref="input"
					type="text"
					class="flex-1 mr-1 control"
					v-model="answer.key"
					autofocus
					@keydown.stop
					@keydown.enter.prevent.stop="saveResponse"
				/>
				<button @click="saveResponse" class="btn btn-green is-sm">Save</button>
			</div>
			<div class="flex" v-else>
				<p class="flex-1 mr-1" :class="{'text-grey': !answer}" v-text="answer.key"></p>
				<button @click="editResponse" class="btn is-blue is-sm">Edit</button>
			</div>
		</div>
	</div>
</template>
<script>
import {nextTick} from 'vue';
export default {
	props: {
		dataQuestion: { required: true },
		dataQuestionAttachments: { required: true },
		index: { required: true },
		value: { default: null }
	},
    emits: ['input'],
	data() {
		return {
			highlightedOptionIndex: 0,
			editing: false,
			answer: this.value || { key: "" },
			keyEvents: {
				ArrowDown: () => this.highlightNextOption(),
				ArrowUp: () => this.highlightPreviousOption(),
				Space: () => this.toggleOption(this.highlightedOptionIndex),
				Delete: () => this.clearOption(),
				Backspace: () => this.clearOption()
			}
		};
	},
	computed: {
		choicesCount() {
			return this.dataQuestion.choices.length;
		},
		escapedCode() {
			if (this.dataQuestion.code) {
				return this.dataQuestion.code
					.replace(/\n/g, "\\n")
					.replace(/<br>/g, "\n");
			}
			return null;
		}
	},
	methods: {
		highlightOption(index) {
			this.highlightedOptionIndex = index;
		},
		highlightNextOption() {
			this.highlightedOptionIndex =
				(this.highlightedOptionIndex + 1) % this.choicesCount;
		},
		highlightPreviousOption() {
			this.highlightedOptionIndex =
				this.highlightedOptionIndex <= 0
					? this.choicesCount - 1
					: this.highlightedOptionIndex - 1;
		},
		toggleOption(index) {
			if (this.isSelected(index)) {
				this.answer = { key: "" };
				this.$emit("input", this.answer);
			} else {
				this.answer = this.dataQuestion.choices[index];
				this.$emit("input", this.answer);
			}
		},
		isSelected(index) {
			const choice = this.dataQuestion.choices[index];
			return choice && this.value && this.value.key == choice.key;
		},
		isHighlighted(index) {
			return this.highlightedOptionIndex == index;
		},
		clearResponse() {
			this.$emit("input", { key: "" });
		},
		editResponse() {
			this.editing = true;
			nextTick(() => this.$refs.input.focus());
		},
		saveResponse() {
			this.editing = false;
			this.$emit("input", this.answer);
		}
	},
	watch: {
		value() {
			this.answer = this.value || { key: "" };
		}
	},
	mounted() {
		if (this.dataQuestion.choices.length > 0) {
			window.addEventListener("keydown", ({ code }) => {
				if (this.keyEvents.hasOwnProperty(code)) {
					this.keyEvents[code]();
					return false;
				}
			});
		}
	},
	created() {
		document.addEventListener("DOMContentLoaded", event => {
			document.querySelectorAll("pre code").forEach(block => {
				hljs.highlightBlock(block);
			});
		});
	},
	updated() {
		document.querySelectorAll("pre code").forEach(block => {
			hljs.highlightBlock(block);
		});
	}
};
</script>

