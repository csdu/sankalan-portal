<template>
	<div class="markdown-body"
		v-html="compiledHTML"
	></div>
</template>
<script>
import md from "markdown-it";
import mk from "markdown-it-katex";
import mdhljs from "markdown-it-highlightjs/core";
import hljs from 'highlight.js/lib/core';
import javascript from 'highlight.js/lib/languages/javascript';
import typescript from 'highlight.js/lib/languages/typescript';
import java from 'highlight.js/lib/languages/java';
import cpp from 'highlight.js/lib/languages/cpp';
import csharp from 'highlight.js/lib/languages/csharp';
import python from 'highlight.js/lib/languages/python';
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import html from 'highlight.js/lib/languages/xml';
import json from 'highlight.js/lib/languages/json';
import sql from 'highlight.js/lib/languages/sql';
import go from 'highlight.js/lib/languages/go';
import php from 'highlight.js/lib/languages/php';
import ruby from 'highlight.js/lib/languages/ruby';
import rust from 'highlight.js/lib/languages/rust';
import kotlin from 'highlight.js/lib/languages/kotlin';

import debounce from 'lodash.debounce';

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
	async created() {
        hljs.registerLanguage('javascript', javascript);
        hljs.registerLanguage('typescript', typescript);
        hljs.registerLanguage('java', java);
        hljs.registerLanguage('cpp', cpp);
        hljs.registerLanguage('csharp', csharp);
        hljs.registerLanguage('python', python);
        hljs.registerLanguage('bash', bash);
        hljs.registerLanguage('css', css);
        hljs.registerLanguage('html', html);
        hljs.registerLanguage('json', json);
        hljs.registerLanguage('sql', sql);
        hljs.registerLanguage('go', go);
        hljs.registerLanguage('php', php);
        hljs.registerLanguage('ruby', ruby);
        hljs.registerLanguage('rust', rust);
        hljs.registerLanguage('kotlin', kotlin);
		this.parser = md().disable(["heading"]);
		this.parser.use(mk)
		this.parser.use(mdhljs, {hljs});
	},
	async mounted() {
		this.convertToHtml();
	}
};
</script>
