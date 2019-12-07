import {orderBy as _orderBy} from 'lodash';

export default {
	computed: {
		orderedStepsAsc() {
			return _orderBy(
				this.steps, 'order', 'asc'
			)
		},

		orderedStepsDesc() {
			return _orderBy(
				this.steps, 'order', 'desc'
			)
		},

		firstStep() {
			return this.orderedStepsAsc[0];
		},

		nextStep() {
			return this.orderedStepsAsc.find(
				(s) => s.order > this.currentStep.order
			) || null;
		},

		previousStep() {
			return this.orderedStepsDesc.find(
				(s) => s.order < this.currentStep.order
			) || null;
		},

		currentStep() {
			return this.orderedStepsAsc.find(
				(s) => s.uuid === this.$route.query.step
			) || this.firstStep;
		},

		currentStepIndex() {
			return this.orderedStepsAsc.map(
				(s) => s.uuid
			)
				.indexOf(this.currentStep.uuid)
		}
	},
}
