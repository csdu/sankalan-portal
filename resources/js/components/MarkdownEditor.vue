<template>
	<div class>
		<div class="my-2">
			<button v-if="preview" class="btn is-blue is-sm" @click.prevent="preview = false">Edit</button>
			<button v-else class="btn is-blue is-sm" @click.prevent="convertToHtml(); preview = true">Preview</button>
		</div>
		<div class="control">
			<div
				v-show="preview"
				class="markdown-body control border rounded py-2 my-2 px-4 h-64 overflow-y-scroll"
				v-html="compiledHTML"
			></div>
			<textarea
				@keydown.tab.prevent="tabber($event)"
				:name="name"
				rows="10"
				v-show="!preview"
				v-model="markdown"
				@input="convertToHtml()"
				class="control"
				style="resize: none;"
				placeholder="Use Markdown"
			></textarea>
			<input type="hidden" name="compiledHTML" v-model="compiledHTML" />
		</div>
	</div>
</template>
<script>
import md from "markdown-it";
import mk from "markdown-it-katex";
export default {
	props: {
		value: { default: "" },
		name: { default: "text" }
	},
	data() {
		return {
			parser: null,
			preview: false,
			markdown: this.value,
			compiledHTML: ""
		};
	},
	methods: {
		convertToHtml() {
			document.querySelectorAll("pre code").forEach(block => {
				hljs.highlightBlock(block);
			});

			this.compiledHTML = this.parser.render(this.markdown);
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
		this.parser = md();
		this.parser.use(mk);
		this.convertToHtml();
	}
};
</script>