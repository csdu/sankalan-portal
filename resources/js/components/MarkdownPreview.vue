<template>
	<div ref="preview" class="markdown-body"
		v-html="compiledHTML"
	></div>
</template>
<script>
import md from "markdown-it";
import mk from "markdown-it-katex";
import hljs from 'highlight.js';
import mdhljs from "markdown-it-highlightjs";
import debounce from 'lodash/debounce';

export default {
	props: {
		markdown: { required: true },
	},
	data() {
		return {
			parser: null,
			compiledHTML: ""
		};
	},
	watch: {
		markdown: {
			handler() {
				this.debouncedConvertToHtml();
			},
			immediate: true
		}
	},
	methods: {
		debouncedConvertToHtml: debounce(async function() {
			this.convertToHtml();
		}, 500),

		convertToHtml() {
			this.compiledHTML = this.parser.render(this.markdown);
		},
	},
	created() {
		this.parser = md().disable(["heading"]);
		this.parser.use(mk)
		this.parser.use(mdhljs, {hljs});
	},
	async mounted() {
		this.convertToHtml();
	}
};
</script>