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
						<nuxt-link :to="{
								name: 'author-id',
								params: {
									id: snippet.author.data.username
								}
							}"
						>
							{{ snippet.author.data.name }}
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
						<step-editor :step="currentStep"
									 v-model="currentStep.body"
						/>
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
						/>
					</div>

					<div class="border-t-2 border-gray-300 py-6">
						<h1 class="text-xl text-gray-600 font-medium mb-6">
							Publishing
						</h1>

						<div class="text-gray-500 text-sm mb-6">
							<template v-if="lastSaved">
								Last save at {{ lastSavedFormatted }}
							</template>
							<template v-else>
								No changes saved in this session yet
							</template>
						</div>

						<div class="flex items-baseline">
							<input type="checkbox"
								   name="pubic"
								   id="public"
								   class="mr-2"
								   v-model="snippet.is_public"
							/>

							<div>
								<label for="public" class="text-gray-600 font-medium">
									Make this snippet public
								</label>

								<div class="text-gray-500 text-sm">
									Don't worry, you can change this later.
								</div>
							</div>
						</div>
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
	import moment from 'moment';

	import StepList from "../components/StepList";
	import StepNavigationButton from "../components/StepNavigationButton";
	import AddStepButton from "./components/AddStepButton";
	import DeleteStepButton from "./components/DeleteStepButton";
	import StepEditor from "./components/StepEditor";

	import browseSnippet from "@/mixins/snippets/browseSnippet";

	export default {
		data() {
			return {
				snippet: null,
				steps: [],

				lastSaved: null,
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
			DeleteStepButton,
			StepEditor
		},

		// this will run on the server side before it is available, instead of this.$axios
		async asyncData({app, params}) {
			let snippet = await app.$axios.$get(`snippets/${params.id}`);

			return {
				snippet: snippet.data,
				steps: snippet.data.steps.data
			}
		},

		middleware: [
			'auth'
		],

		computed: {
			lastSavedFormatted() {
				return moment(this.lastSaved).format('hh:mm:ss');
			}
		},

		methods: {
			touchLastSaved() {
				this.lastSaved = moment.now();
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

					this.touchLastSaved();
				}, 500)
			},

			'snippet.is_public': {
				handler: _debounce(async function (is_public) {
					await this.$axios.$patch(`snippets/${this.snippet.uuid}`, {is_public});

					this.touchLastSaved();
				}, 500)
			},

			currentStep: {
				deep: true, //listen to all properties

				handler: _debounce(async function (step) {
					await this.$axios.$patch(`snippets/${this.snippet.uuid}/steps/${step.uuid}`, {
						title: step.title,
						body: step.body
					});

					this.touchLastSaved();
				}, 500)
			}
		}
	}
</script>
