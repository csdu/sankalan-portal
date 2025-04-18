<template>
	<div class>
		<div class="control flex">
			<div class="w-1/2">
				<label class="control">Question</label>
				<textarea
					@keydown.tab.prevent="tabber($event)"
					:name="name"
					rows="10"
					v-model="markdown"
					class="control"
					style="resize: none;"
					placeholder="Use Markdown"
				></textarea>
			</div>
			<div ref="preview" class="w-1/2 flex flex-col">
				<label class="control">Markdown Preview</label>
				<MarkdownPreview
					class="control h-full border rounded py-2 w-full ml-1 px-4 mb-2 overflow-y-scroll"
					:markdown="markdown"
				></MarkdownPreview>
			</div>
		</div>
	</div>
</template>
<script>
import MarkdownPreview from "./MarkdownPreview.vue";

export default {
	components: {
		MarkdownPreview,
	},
	props: {
		value: { default: "" },
		name: { default: "text" }
	},
	data() {
		return {
			markdown: this.value,
		};
	},
	methods: {
		tabber(event) {
			let text = event.target.value,
				originalSelectionStart = event.target.selectionStart,
				textStart = text.slice(0, originalSelectionStart),
				textEnd = text.slice(originalSelectionStart);

			event.target.value = `${textStart}\t${textEnd}`;
			event.target.value = event.target.value; // required to make the cursor stay in place.
			event.target.selectionEnd = event.target.selectionStart =
				originalSelectionStart + 1;
		}
	},
};
</script>
