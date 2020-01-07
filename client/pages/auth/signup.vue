<template>
	<div class="container mt-16">
		<div class="flex flex-col items-center">
			<h1 class="text-3xl text-gray-700 font-medium mb-10">
				Get snippeting.
			</h1>

			<form class="bg-white p-8 rounded w-full md:w-8/12 lg:w-7/12 mb-6"
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

				<div class="flex flex-wrap lg:flex-no-wrap">
					<div class="lg:mr-6 w-full lg:w-6/12 mb-6">
						<label class="block text-gray-600 font-medium mb-2"
							   :class="{'text-red-500' : validation.name}"
							   for="email"
						>
							Name
						</label>
						<input class="border-2 border-gray-400 rounded block w-full p-3"
							   :class="{'border-red-500' : validation.name}"
							   type="text"
							   name="name"
							   id="name"
							   v-model="form.name"
						>

						<div class="text-red-500 mb-4 text-sm mt-1 font-medium" v-if="validation.name">
							{{ validation.name[0] }}
						</div>
					</div>

					<div class="w-full lg:w-6/12 mb-6">
						<label class="block text-gray-600 font-medium mb-2"
							   :class="{'text-red-500' : validation.username}"
							   for="email"
						>
							Username
						</label>
						<input class="border-2 border-gray-400 rounded block w-full p-3"
							   :class="{'border-red-500' : validation.username}"
							   type="text"
							   name="username"
							   id="username"
							   v-model="form.username"
						>

						<div class="text-red-500 mb-4 text-sm mt-1 font-medium" v-if="validation.username">
							{{ validation.username[0] }}
						</div>
					</div>
				</div>

				<div class="flex flex-wrap lg:flex-no-wrap">
					<div class="lg:mr-6 w-full lg:w-6/12 mb-6">
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

					<div class="w-full lg:w-6/12 mb-6">
						<label class="block text-gray-600 font-medium mb-2"
							   :class="{'text-red-500' : validation.password_confirmation}"
							   for="password_confirmation"
						>
							Password Confirmation
						</label>
						<input class="border-2 border-gray-400 rounded block w-full p-3"
							   :class="{'border-red-500' : validation.password_confirmation}"
							   type="password"
							   name="password_confirmation"
							   id="password_confirmation"
							   v-model="form.password_confirmation"
						>
						<div class="text-red-500 mb-4 text-sm mt-1 font-medium" v-if="validation.password_confirmation">
							{{ validation.password_confirmation[0] }}
						</div>
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
					name: '',
					username: '',
					password: '',
					password_confirmation: ''
				},

				validation: {}
			}
		},

		head() {
			return {
				title: 'Sign up'
			}
		},

		methods: {
			async submit() {
				try {
					await this.$axios.$post('auth/signup', this.form);

					await this.$auth.loginWith('local', {
						data: {
							email: this.form.email,
							password: this.form.password
						}
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
