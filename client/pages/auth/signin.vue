<template>
	<div class="container mt-16">
		<div class="flex flex-col items-center">
			<h1 class="text-3xl text-gray-700 font-medium mb-10">
				Hello
			</h1>

			<form class="bg-white p-8 rounded w-full md:w-6/12 lg:w-4/12 mb-6"
				  @submit.prevent="submit"
				  action=""
			>
				<div class="mb-6">
					<label class="block text-gray-600 font-medium mb-2"
						   :class="{'text-red-500' : validation.email}"
						   for="email"
					>
						Email address
					</label>
					<input class="border-2 border-gray-400 rounded block w-full p-3"
						   :class="{'border-red-500' : validation.email}"
						   type="text"
						   name="email"
						   id="email"
						   v-model="form.email"
					>

					<div class="text-red-500 mb-4 text-sm mt-1 font-medium" v-if="validation.email">
						{{ validation.email[0] }}
					</div>
				</div>
				<div class="mb-6">
					<label class="block text-gray-600 font-medium mb-2"
						   :class="{'text-red-500' : validation.password}"
						   for="password"
					>
						Password
					</label>
					<input class="border-2 border-gray-400 rounded block w-full p-3"
						   :class="{'border-red-500' : validation.password}"
						   type="password"
						   name="password"
						   id="password"
						   v-model="form.password"
					>
					<div class="text-red-500 mb-4 text-sm mt-1 font-medium" v-if="validation.password">
						{{ validation.password[0] }}
					</div>
				</div>
				<div>
					<button class="bg-blue-500 text-white p-4 rounded text-center font-medium block w-full"
							type="submit"
					>
						Sign In
					</button>
				</div>
			</form>

			<div class="text-center text-gray-600">
				No account?
				<nuxt-link :to="{name: 'index'}">
					Create one here
				</nuxt-link>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					email: '',
					password: '',
				},

				validation: {}
			}
		},

		methods: {
			async submit() {
				try {
					await this.$auth.loginWith('local', {
						data: this.form
					})
				} catch (e) {
					if (e.response.status === 422) {
						this.validation = e.response.data.errors
					}
				}
			}
		}
	}
</script>
