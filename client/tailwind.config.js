module.exports = {
	theme: {
		extend: {
			fontFamily: {
				header: ['Rubik', 'sans-serif']
			}
		},

		rotate: {
			'45': '40deg',
			'-45': '-40deg'
		}
	},
	variants: {},
	plugins: [
		require('tailwindcss-transforms')({})
	]
};
