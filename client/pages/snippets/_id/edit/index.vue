<template>
	<div>

		<div class="bg-white mb-16">
			<div class="container py-10 pb-16">
				<div class="w-10/12">
					<input type="text"
						   class="text-4xl text-gray-700 font-medium font-header leading-tight mb-4 w-full block p-2 border-2 rounded border-dashed border-gray-400"
						   value=""
						   placeholder="Untitled snippet"
						   v-model="snippet.title"
					>
					<div class="text-gray-600">
						Created by
						<nuxt-link :to="{}"
						>
							Danni
						</nuxt-link>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="flex items-center mb-6">
				<div class="text-xl text-gray-600 font-medium mr-3 font-header">
					{{ currentStepIndex + 1 }}/{{ steps.length }}.
				</div>

				<input type="text"
					   class="text-xl text-gray-600 font-medium py-1 p-2 bg-transparent border-2 rounded border-dashed border-gray-400 w-full font-header"
					   value=""
					   placeholder="Untitled step"
					   v-model="currentStep.title"
				>
			</div>

			<div class="flex flex-wrap lg:flex-no-wrap">
				<div class="w-full lg:w-8/12 lg:mr-16 flex flex-wrap lg:flex-no-wrap justify-between items-start mb-8">
					<div class="flex flex-row lg:flex-col mr-2 order-first">
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
						<add-step-button :snippet="snippet"
										 :currentStep="currentStep"
										 position="before"
										 @added="handleStepAdded"
						/>
					</div>

					<div class="w-full lg:mr-2">
						<textarea class="w-full mb-6 border-dashed border-2 border-gray-400 rounded-lg"
								  v-model="currentStep.body"
						>
						</textarea>
						<div class="bg-white p-8 rounded-lg text-gray-600">
							Markdown content
						</div>
					</div>
					<div class="flex flex-row-reverse lg:flex-col order-first lg:order-last">
						<step-navigation-button :step="nextStep">
							<svg xmlns="http://www.w3.org/2000/svg"
								 viewBox="0 0 24 24"
								 class="fill-current text-white h-6 w-6"
							>
								<path
									d="M18.59 13H3a1 1 0 0 1 0-2h15.59l-5.3-5.3a1 1 0 1 1 1.42-1.4l7 7a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.42-1.4l5.3-5.3z"/>
							</svg>
						</step-navigation-button>

						<add-step-button :snippet="snippet"
										 :currentStep="currentStep"
										 position="after"
										 @added="handleStepAdded"
						/>

						<delete-step-button :snippet="snippet"
											:step="currentStep"
											v-if="steps.length > 1"
											@deleted="handleStepDeleted"
						/>
					</div>
				</div>
				<div class="w-full lg:w-4/12">
					<div class="mb-8">
						<h1 class="text-xl text-gray-600 font-medium mb-6">
							Steps
						</h1>

						<step-list :steps="orderedStepsAsc"
								   :currentStep="currentStep"
						>
						</step-list>
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
	import {debounce as _debounce} from 'lodash';

	import StepList from "../components/StepList";
	import StepNavigationButton from "../components/StepNavigationButton";
	import AddStepButton from "./components/AddStepButton";
	import DeleteStepButton from "./components/DeleteStepButton";

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
				title: `Editing ${this.snippet.title}` || 'Untitled snippet'
			}
		},

		components: {
			StepList,
			StepNavigationButton,
			AddStepButton,
			DeleteStepButton
		},

		// this will run on the server side before it is available, instead of this.$axios
		async asyncData({app, params}) {
			let snippet = await app.$axios.$get(`snippets/${params.id}`);

			return {
				snippet: snippet.data,
				steps: snippet.data.steps.data
			}
		},

		methods: {
			goToStep(step) {
				this.$router.push({
					query: {
						step: step.uuid
					}
				});
			},

			handleStepAdded(step) {
				this.steps.push(step);
				this.goToStep(step);
			},

			handleStepDeleted(step) {
				let previousStep = this.previousStep;

				this.steps = this.steps.filter((s) => {
					return s.uuid !== step.uuid
				});

				this.goToStep(previousStep || this.firstStep)
			}
		},

		watch: {
			'snippet.title': {
				handler: _debounce(async function (title) {
					await this.$axios.$patch(`snippets/${this.snippet.uuid}`, {title});
				}, 500)
			},

			currentStep: {
				deep: true, //listen to all properties

				handler: _debounce(async function (step) {
					await this.$axios.$patch(`snippets/${this.snippet.uuid}/steps/${step.uuid}`, {
						title: step.title,
						body: step.body
					});
				}, 500)
			}
		}
	}
</script>
