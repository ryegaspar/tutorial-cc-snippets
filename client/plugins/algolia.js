import algoliasearch from 'algoliasearch/lite';
import { createInstantSearch } from "vue-instantsearch";

export default ({ app }, inject) => {
	const searchClient = algoliasearch(
		'XUPD9OPZQJ',
		'6d707339fe29ebe0239f33f9d9957cdb'
	);

	const { instantsearch } = createInstantSearch({
		searchClient,
		indexName: 'snippets'
	});

	inject('instantsearch', instantsearch);
};
