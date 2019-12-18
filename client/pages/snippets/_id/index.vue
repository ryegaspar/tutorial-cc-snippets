<template>
	<div>

		<div class="bg-white mb-16">
			<div class="container py-10 pb-16">
				<div class="w-10/12">
					<h1 class="text-4xl text-gray-700 font-medium leading-tight mb-4">
						{{ snippet.title || 'Untitled snippet' }}
					</h1>

					<div class="text-gray-600">
						Created by
						<nuxt-link :to="{}"
						>
							{{ snippet.author.data.name }}
						</nuxt-link>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<h1 class="text-xl text-gray-600 font-medium mb-6">
				{{ currentStepIndex + 1 }}/{{ steps.length }}. {{ currentStep.title }}
			</h1>

			<div class="flex flex-wrap lg:flex-no-wrap">
				<div class="w-full lg:w-8/12 lg:mr-16 flex flex-wrap lg:flex-no-wrap justify-between items-start mb-8">
					<div class="order-first mr-2">
						<step-navigation-button :step="previousStep"
						>
							<svg xmlns="http://www.w3.org/2000/svg"
								 viewBox="0 0 24 24"
								 class="fill-current text-white h-6 w-6"
							>
								<path
									d="M5.41 11H21a1 1 0 0 1 0 2H5.41l5.3 5.3a1 1 0 0 1-1.42 1.4l-7-7a1 1 0 0 1 0-1.4l7-7a1 1 0 0 1 1.42 1.4L5.4 11z"/>
							</svg>
						</step-navigation-button>
					</div>

					<div class="bg-white p-8 rounded-lg text-gray-600 w-full lg:mr-2">
						<step-markdown :value="currentStep.body"/>
					</div>

					<div class="flex flex-row lg:flex-col order-first lg:order-last ">
						<step-navigation-button :step="nextStep">
							<svg xmlns="http://www.w3.org/2000/svg"
								 viewBox="0 0 24 24"
								 class="fill-current text-white h-6 w-6"
							>
								<path
									d="M18.59 13H3a1 1 0 0 1 0-2h15.59l-5.3-5.3a1 1 0 1 1 1.42-1.4l7 7a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.42-1.4l5.3-5.3z"/>
							</svg>
						</step-navigation-button>

						<nuxt-link :to="{
											name: 'snippets-id-edit',
											params: {
												id: snippet.uuid
											},
											query: {
												step: currentStep.uuid
											}
										}"
								   v-if="snippet.user.data.owner"
								   class="block mb-2 p-3 bg-blue-500 rounded-lg order-first lg:order-last mr-2 lg:mr-0"
								   title="Edit step"
						>
							<svg xmlns="http://www.w3.org/2000/svg"
								 viewBox="0 0 24 24"
								 class="fill-current text-white h-6 w-6"
							>
								<path
									d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/>
							</svg>
						</nuxt-link>
					</div>
				</div>
				<div class="w-full lg:w-4/12">
					<div class="mb-8">
						<h1 class="text-xl text-gray-600 font-medium mb-6">
							Steps
						</h1>

						<step-list :steps="orderedStepsAsc"
								   :currentStep="currentStep"
						/>
					</div>

					<div class="text-gray-500 text-sm">
						Use
						<div class="inline-block px-2 leading-relaxed  text-gray-600 rounded bg-gray-400 text-sm">ctrl
						</div>
						+
						<div class="inline-block px-2 leading-relaxed  text-gray-600 rounded bg-gray-400 text-sm">
							shift
						</div>
						+
						<div class="inline-block px-2 leading-relaxed  text-gray-600 rounded bg-gray-400 text-sm">left
						</div>
						or
						<div class="inline-block px-2 leading-relaxed  text-gray-600 rounded bg-gray-400 text-sm">
							right
						</div>
						on your keyboard to navigate between steps
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import StepList from "./components/StepList";
	import StepNavigationButton from "./components/StepNavigationButton";
	import StepMarkdown from "../../../components/snippets/StepMarkdown";

	import browseSnippet from "@/mixins/snippets/browseSnippet";

	export default {
		data() {
			return {
				snippet: null,
				steps: []
			}
		},

		mixins: [
			browseSnippet
		],

		head() {
			return {
				title: `${this.snippet.title}` || 'Untitled snippet'
			}
		},

		components: {
			StepList,
			StepNavigationButton,
			StepMarkdown
		},

		async asyncData({app, params}) {
			let snippet = await app.$axios.$get(`snippets/${params.id}`);

			return {
				snippet: snippet.data,
				steps: snippet.data.steps.data,
			}
		},
	}
</script>

