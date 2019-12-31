<template>
	<ais-instant-search-ssr>
		<div>
			<div class="bg-white mb-16">
				<div class="container py=10 pb-16">
					<div class="flex justify-between items-center mb-6">
						<h1 class="text-4xl text-gray-700 font-medium leading-tight mb-4">
							Search
						</h1>
						<client-only>
							<ais-powered-by/>
						</client-only>
					</div>

					<ais-search-box placeholder="Search snippets"
									:class-names="{
										'ais-SearchBox': 'w-full',
										'ais-SearchBox-input': 'w-full border-2 border-gray-400 rounded-lg block p-4 text-lg',
										'ais-SearchBox-submit': 'hidden',
										'ais-SearchBox-submitIcon': 'hidden',
										'ais-SearchBox-reset': 'hidden'
									}"
					/>
				</div>
			</div>

			<div class="container">
				<ais-state-results>
					<div slot-scope="{ query }">
						<template v-if="query.length">
							<ais-stats>
								<h1 class="text-xl text-gray-600 font-medium mb-6"
									slot-scope="{ nbHits }"
								>
									Snippets ({{ nbHits }})
								</h1>
							</ais-stats>

							<ais-hits>
								<div slot="item"
									 slot-scope="{ item }"
								>
									<search-snippet-card :snippet="item.data">

									</search-snippet-card>
								</div>
							</ais-hits>
						</template>
					</div>
				</ais-state-results>
			</div>
		</div>

	</ais-instant-search-ssr>
</template>

<script>
	import SearchSnippetCard from "./components/SearchSnippetCard";
	import {
		AisInstantSearchSsr,
		AisHits,
		AisSearchBox,
		AisPoweredBy,
		AisStats,
		AisStateResults
	} from 'vue-instantsearch';

	export default {
		head() {
			return {
				title: 'Search'
			}
		},

		beforeMount() {
			this.$instantsearch.hydrate(this.instantSearchState)
		},

		async asyncData({app}) {
			const instantsearch = app.$instantsearch;

			return instantsearch
				.findResultsState({
					hitsPerPage: 20
				})
				.then(() => {
					return {
						instantSearchState: instantsearch.getState()
					}
				})
		},

		data() {
			return {
				instantSearchState: null,
			}
		},

		provide() {
			return {
				$_ais: this.$instantsearch
			}
		},

		components: {
			AisInstantSearchSsr,
			AisHits,
			AisSearchBox,
			AisPoweredBy,
			AisStats,
			AisStateResults,
			SearchSnippetCard
		}
	}
</script>
