<template>
	<div>
		<the-navigation></the-navigation>
		<nuxt/>
	</div>
</template>

<script>
	import TheNavigation from "@/components/global/TheNavigation";

	export default {
		components: {
			TheNavigation
		}
	}
</script>
