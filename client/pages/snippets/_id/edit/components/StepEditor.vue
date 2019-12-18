<template>
	<div>
		<textarea ref="textarea"
		>
		</textarea>
		<div class="bg-white p-8 rounded-lg text-gray-600">
			<step-markdown :value="step.body"/>
		</div>
	</div>
</template>

<script>
	import StepMarkdown from "../../../../../components/snippets/StepMarkdown";

	export default {
		data () {
			return {
				codemirror: null,
			}
		},

		props: {
			step: {
				required: true,
				type: Object,
			}
		},

		components: {
			StepMarkdown
		},

		computed: {
			document () {
				return this.codemirror.getDoc()
			}
		},

		watch: {
			'step.uuid' () {
				this.document.setValue(this.step.body)
			}
		},

		mounted() {
			let CodeMirror = require('codemirror');

			this.codemirror = CodeMirror.fromTextArea(this.$refs.textarea, {

			});

			this.document.setValue(this.step.body);

			this.document.on('change', (document) => {
				this.$emit('input', document.getValue());
			});
		}
	}
</script>

<style lang="scss">
	.CodeMirror {
		@apply font-sans text-base w-full max-w-full border-dashed border-2 border-gray-400 rounded-lg mb-6 p-8;
	}
</style>
