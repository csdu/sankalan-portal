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
					<svg viewBox="0 0 110 110" class="text-blue w-8 mr-2">
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
						<animateTransform
							xlink:href="#loader-circle"
							attributeName="transform"
							attributeType="XML"
							type="rotate"
							from="0 55 55"
							to="360 55 55"
							dur="3s"
							repeatCount="indefinite"
						/>
					</svg>
					<span class="text-gray-600">saving response...</span>
				</span>
				<!-- <button v-else class="btn is-green" @click="submit()">End Quiz</button> -->
				<!-- <button :disabled=loading class="btn is-green mx-2" :class="{'cursor-wait' : loading}" @click="saveResponse()">Save</button> -->
			</div>
		</div>
		<div class="navigation flex flex-col md:w-64 px-3 overflow-auto py-4">
			<ul class="questions-nav list-reset justify-center flex flex-wrap -mx-1 -mb-1 mt-4">
				<li v-for="questionNumber in questions.length" :key="questionNumber">
					<button
						class="mx-1 my-1 w-6 h-6 p-0 flex justify-center items-center text-xs border border-black text-black rounded"
						v-text="questionNumber"
						@click.prevent="setCurrentQuestion(questionNumber-1)"
						:class="{
                    'border-black bg-black text-white': isCurrentQuestion(questionNumber-1),
                    'border-green bg-green hover:bg-green-dark text-white': isQuestionAnswered(questionNumber-1),
                    'border-red bg-red hover:bg-red-dark text-white': isQuestionSkipped(questionNumber-1)
                }"
					></button>
				</li>
			</ul>
			<div class="flex justify-center">
				<countdown-timer
					:duration="timeLimit"
					:hurry="300"
					class="inline-flex justify-center items-baseline my-8 text-lg font-bold font-mono"
					:class="{'animation-vibrate text-red': hurry}"
					@timeup="endQuiz"
					@hurryup="hurry=true"
				></countdown-timer>
			</div>
			<div class="mb-auto text-center">
				<button class="btn is-red" @click.prevent="submit">End Quiz</button>
			</div>
		</div>
	</div>
	<div
		class="fixed inset-x-0 top-0 z-50 w-full h-screen flex flex-col justify-center items-center"
		v-else
	>
		<svg viewBox="0 0 100 100" class="text-green w-32" v-if="submission.done && submission.success">
			<path
				d="M10 50 L40 80 L90 10"
				stroke="currentColor"
				fill="none"
				stroke-width="15"
				stroke-dasharray="129"
			/>
		</svg>
		<svg viewBox="0 0 100 100" class="text-red w-32" v-else-if="submission.done">
			<path
				d="M10 10 L90 90"
				stroke="currentColor"
				fill="none"
				stroke-width="15"
				stroke-dasharray="114"
			/>
			<path
				d="M90 10 L10 90"
				stroke="currentColor"
				fill="none"
				stroke-width="15"
				stroke-dasharray="114"
			/>
		</svg>
		<svg viewBox="0 0 110 110" class="text-blue w-32" v-else>
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
			<animateTransform
				xlink:href="#loader-circle"
				attributeName="transform"
				attributeType="XML"
				type="rotate"
				from="0 55 55"
				to="360 55 55"
				dur="3s"
				repeatCount="indefinite"
			/>
		</svg>
		<p class="text-center my-6" v-text="submission.text"></p>
		<p class="text-center my-6" v-if="submission.success">
			You will be redirected to dashboard in 3 seconds.
			Click
			<button
				@click="redirect"
				class="font-normal text-blue hover:undeline"
			>here</button> to redirect manually.
		</p>
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
