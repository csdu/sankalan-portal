<template>
	<div class>
		<div class="my-2">
			<button v-if="preview" class="btn is-blue is-sm" @click.prevent="preview = false">Edit</button>
			<button v-else class="btn is-blue is-sm" @click.prevent="convertToHtml(); preview = true">Preview</button>
		</div>
		<div class="control">
			<div
				v-if="preview"
				class="markdown-body control border rounded py-2 px-3 h-64 overflow-y-scroll"
				v-html="compiledHTML"
			></div>
			<textarea
				name="text"
				rows="10"
				v-else
				v-model="markdown"
				@input="$emit('input', markdown)"
				class="control"
				style="resize: none;"
				placeholder="Use Markdown"
			></textarea>
		</div>
	</div>
</template>
<script>
import md from "markdown-it";
import mk from "markdown-it-katex";
export default {
	props: {
		value: { default: "" }
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
			this.compiledHTML = this.parser.render(this.markdown);
		}
	},
	created() {
		this.parser = md();
		this.parser.use(mk);
		this.convertToHtml();
	}
};
</script>