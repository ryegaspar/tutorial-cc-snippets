<template>
	<div>
		<the-navigation/>
		<nuxt/>
		<the-footer />
	</div>
</template>

<script>
	import TheNavigation from "@/components/global/TheNavigation";
	import TheFooter from "@/components/global/TheFooter";

	export default {
		components: {
			TheNavigation,
			TheFooter
		}
	}
</script>
