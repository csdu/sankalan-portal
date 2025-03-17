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
					@input="debouncedConvertToHtml"
					class="control"
					style="resize: none;"
					placeholder="Use Markdown"
				></textarea>
			</div>
			<div ref="preview" class="w-1/2 flex flex-col">
				<label class="control">Markdown Preview</label>
				<div 
					class="markdown-body control h-full border rounded py-2 w-full ml-1 px-4 mb-2 overflow-y-scroll"
					v-html="compiledHTML"
				></div>
			</div>
			<input type="hidden" name="compiledHTML" v-model="compiledHTML" />
		</div>
	</div>
</template>
<script>
import md from "markdown-it";
import mk from "markdown-it-katex";
import { nextTick } from 'vue';
import debounce from 'lodash/debounce';

export default {
	props: {
		value: { default: "" },
		name: { default: "text" }
	},
	data() {
		return {
			parser: null,
			markdown: this.value,
			compiledHTML: ""
		};
	},
	methods: {
		async convertToHtml() {
			this.compiledHTML = this.parser.render(this.markdown);
			await nextTick();
			this.highlight();
		},
		highlight() {
			this.$refs?.preview.querySelectorAll("pre code").forEach(block => {
				hljs.highlightBlock(block);
			});
		},
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
	created() {
		this.parser = md().disable(["heading"]);
		this.parser.use(mk);
		this.debouncedConvertToHtml = debounce(this.convertToHtml, 500);
		this.convertToHtml();
	},
};
</script>