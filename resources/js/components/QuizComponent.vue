<template>
	<div class="flex flex-1" v-if="isLive">
		<div class="question-area flex flex-col w-full lg:w-3/4 px-4 overflow-auto py-4">
			<quiz-question
				class="flex flex-col"
				:data-question="currentQuestion"
				:data-question-attachments="currentQuestionAttachments"
				:index="currentQuestionIndex"
				:value="currentResponse"
				@input="saveResponse"
			></quiz-question>
			<div class="navigate flex">
				<button
					:disabled="loading"
					v-if="!isFirstQuestion"
					class="mr-2 btn"
					:class="{'cursor-wait' : loading}"
					@click="previousQuestion()"
				>Previous</button>
				<button
					:disabled="loading"
					v-if="!isLastQuestion"
					class="mr-2 btn is-blue"
					:class="{'cursor-wait' : loading}"
					@click="nextQuestion()"
				>Next</button>
				<span v-if="loading" class="inline-flex items-center ml-auto">
					<svg viewBox="0 0 110 110" class="text-blue-500 w-8 mr-2">
						<circle
							id="loader-circle"
							cx="55"
							cy="55"
							r="50"
							stroke-dasharray="314.16"
							stroke="currentColor"
							fill="none"
							stroke-width="10"
						/>
						<animate
							xlink:href="#loader-circle"
							attributeName="stroke-dashoffset"
							attributeType="XML"
							from="314.16"
							to="314.16"
							dur="2s"
							begin="0s"
							keyTimes="0; 0.25; 0.5; 0.75; 1;"
							values="314.16; 0; -314.16; 0; 314.16;"
							repeatCount="indefinite"
						/>
					</svg>
					<span>Saving...</span>
				</span>
			</div>
		</div>
		<div class="question-navigation hidden lg:flex flex-col w-1/4 p-4 bg-slate-50">
			<h4 class="mb-4">Question Navigation</h4>
			<div class="flex flex-wrap -mx-1">
				<button
					v-for="(question, index) in questions"
					:key="question.id"
					class="w-10 h-10 m-1 rounded flex items-center justify-center bg-white shadow-1 text-sm hover:shadow-2 transition-all duration-200"
					:class="{
						'bg-emerald-500 text-white': responses[question.id],
						'ring-2 ring-blue-500': currentQuestionIndex === index
					}"
					@click="goToQuestion(index)"
				>{{ index + 1 }}</button>
			</div>
			<button
				:disabled="loading"
				class="mt-auto btn is-green"
				:class="{'cursor-wait' : loading}"
				@click="finish"
			>Finish Quiz</button>
		</div>
	</div>
	<div v-else class="flex flex-col items-center justify-center flex-1">
		<div class="text-center">
			<h3 class="text-2xl mb-4">Time's Up!</h3>
			<p class="text-slate-600 mb-6">Your quiz has been automatically submitted.</p>
			<a :href="redirectTo" class="btn is-blue">Go to Dashboard</a>
		</div>
	</div>
</template>

<script>
import CountdownTimer from "./CountdownTimer.vue";
import QuizQuestion from "./QuizQuestion.vue";
export default {
	components: { CountdownTimer, QuizQuestion },
	props: {
		action: { required: true },
		redirectTo: { required: true },
		saveAction: { required: true },
		dataQuestions: { default: [] },
		dataQuestionsAttachments: { default: [] },
		timeLimit: { default: 30 },
		dataResponses: { default: () => [] }
	},
	data() {
		return {
			isLive: true,
			submission: {
				done: false,
				success: false,
				text: "Please wait! While we record your response."
			},
			keyEvents: {
				ArrowRight: () => this.nextQuestion(),
				ArrowLeft: () => this.previousQuestion(),
				Enter: () => this.submit()
			},
			hurry: false,
			currentQuestionIndex: 0,
			responses: [],
			questions: [],
			loading: false
		};
	},
	computed: {
		currentQuestion() {
			return this.questions[this.currentQuestionIndex];
		},
		currentQuestionAttachments() {
			return this.dataQuestionsAttachments.filter(questionAttachment => {
				return questionAttachment.question_id === this.currentQuestion.id;
			});
		},
		currentResponse() {
			return this.responses[this.currentQuestionIndex];
		},
		isFirstQuestion() {
			return this.currentQuestionIndex === 0;
		},
		isLastQuestion() {
			return this.currentQuestionIndex === this.questions.length - 1;
		}
	},
	methods: {
		isCurrentQuestion(index) {
			return this.currentQuestionIndex == index;
		},
		hasQuestionResponse(index) {
			return this.responses[index] && this.responses[index].key;
		},
		isQuestionAnswered(index) {
			return (
				!this.isCurrentQuestion(index) &&
				this.questions[index].visited &&
				this.hasQuestionResponse(index)
			);
		},
		isQuestionSkipped(index) {
			return (
				!this.isCurrentQuestion(index) &&
				this.questions[index].visited &&
				!this.hasQuestionResponse(index)
			);
		},
		setCurrentQuestion(index) {
			// this.currentResponse = this.currentResponse;

			if (index >= 0 && index < this.questions.length) {
				this.questions[this.currentQuestionIndex].visited = true;
				this.currentQuestionIndex = index;
			}
		},
		nextQuestion() {
			this.setCurrentQuestion(this.currentQuestionIndex + 1);
			// this.currentResponse = '';
		},
		previousQuestion() {
			this.setCurrentQuestion(this.currentQuestionIndex - 1);
			// this.currentResponse = '';
		},
		submit() {
			if (
				confirm(
					"You still have time left. Are you sure you want to submit your Response?"
				)
			) {
				this.endQuiz();
			}
		},
		endQuiz() {
			this.isLive = false;
			axios
				.post(this.action)
				.catch(this.onFailure)
				.then(this.onSuccess);
		},
		onSuccess({ data }) {
			this.submission = {
				done: true,
				success: data.message.level == "success",
				text: data.message.message
			};
			setTimeout(this.redirect, 3 * 1000);
		},
		redirect() {
			window.location.replace(this.redirectTo);
		},
		onFailure({ response }) {
			this.submission = {
				done: true,
				success: response.data.message.level != "danger",
				text: response.data.message.message
			};
		},
		saveResponse(response) {
			if (response == null) {
				return flash("Answer can not be null!", "danger");
			}

			this.loading = true;

			axios
				.post(this.saveAction, {
					question_id: this.currentQuestion.id,
					response_key: response.key
				})
				.catch(error => {
					flash("Error occurred in saving response!", "danger");
				})
				.then(({ data }) => {
					this.responses.splice(this.currentQuestionIndex, 1, response);

					flash(data.message, "info");
				})
				.finally(() => {
					this.loading = false;
				});
		}
	},
	created() {
		this.responses = new Array(this.dataQuestions.length).fill(null);
		this.dataQuestions.sort((a, b) => (a.qno > b.qno ? 1 : -1));

		this.questions = this.dataQuestions.map(question => {
			question.visited = false;
			return question;
		});

		this.dataResponses.forEach(response => {
			const index = this.dataQuestions.findIndex(question => {
				return response.question_id == question.id;
			});

			if (index > -1) {
				this.responses[index] = { key: response.response_keys };
			}
		});
	},
	mounted() {
		window.addEventListener("keydown", ({ code }) => {
			if (this.keyEvents.hasOwnProperty(code)) {
				this.keyEvents[code]();
				return false;
			}
		});
	}
};
</script>
<style>
.animation-vibrate {
	animation: vibrate 1s infinite alternate linear;
}
@keyframes vibrate {
	0% {
		transform: scale(1);
	}
	50% {
		transform: scale(1.2);
	}
	60% {
		transform: rotate(5deg) scale(1.2);
	}
	80% {
		transform: rotate(-5deg) scale(1.2);
	}
	100% {
		transform: rotate(0) scale(1.2);
	}
}
</style>
