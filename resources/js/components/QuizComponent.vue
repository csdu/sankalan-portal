<template>
    <div class="flex h-full" v-if="isLive" 
            @keydown.right="nextQuestion"
            @keydown.left="previousQuestion"
            @keydown.enter="submit">
        <div class="question-area flex flex-col w-full lg:w-3/4 h-full px-4 overflow-auto">
            <slot name="header"></slot>
            <quiz-question 
                class="flex-1"
                :data-question="currentQuestion" 
                :index="currentQuestionIndex" 
                v-model="responses[currentQuestionIndex]">
            </quiz-question>
            <div class="navigate flex">
                <button v-if="!isFirstQuestion" class="mr-2 btn" @click="previousQuestion()">Previous</button>
                <button v-if="!isLastQuestion" class="btn is-green" @click="nextQuestion()">Next</button>
                <button v-else class="btn is-green" @click="submit()">Submit</button>
            </div>
        </div>
        <div class="navigation flex flex-col border-l h-full w-1/4 px-3 overflow-auto">
            <ul class="questions-nav list-reset flex flex-wrap -mx-1 -mb-1 mt-4">
                <li v-for="questionNumber in questions.length" 
                :key="questionNumber" 
                >
                <button 
                class="mx-1 my-1 w-6 h-6 p-0 flex justify-center items-center text-xs border border-black text-black rounded" v-text="questionNumber" 
                @click.prevent="setCurrentQuestion(questionNumber-1)" 
                :class="{
                    'border-black bg-black text-white': isCurrentQuestion(questionNumber-1),
                    'border-green bg-green hover:bg-green-dark text-white': isQuestionAnswered(questionNumber-1),
                    'border-red bg-red hover:bg-red-dark text-white': isQuestionSkipped(questionNumber-1)
                }"></button>
                </li>
            </ul>
            <div class="navigate flex justify-end my-8">
                <button v-if="!isFirstQuestion" class="btn" @click="previousQuestion()">Previous</button>
                <button v-if="!isLastQuestion" class="ml-4 btn is-green" @click="nextQuestion()">Next</button>
                <button v-else class="ml-4 btn is-green" @click="submit()">Submit</button>
            </div>
            <countdown-timer :duration="timeLimit" :hurry="300"
            class="inline-flex justify-center items-baseline my-8 text-lg font-bold font-mono" 
            :class="{'animation-vibrate text-red': hurry}"
            @timeup="endQuiz" @hurryup="hurry=true"></countdown-timer>
            <div class="mt-auto mb-8 text-center">
                <button class="btn is-green" @click.prevent="submit">Submit</button>
            </div>
        </div>
    </div>
    <div class="fixed pin-x pin-t z-50 bg-white h-screen flex flex-col justify-center items-center" v-else>
        <svg viewBox="0 0 100 100" class="text-green w-32" v-if="submission.done && submission.success">
            <path d="M10 50 L40 80 L90 10" stroke="currentColor" fill="none" stroke-width="15" stroke-dasharray="129"></path>	
        </svg>
        <svg viewBox="0 0 100 100" class="text-red w-32" v-else-if="submission.done">
            <path d="M10 10 L90 90" stroke="currentColor" fill="none" stroke-width="15" stroke-dasharray="114" ></path>	
            <path d="M90 10 L10 90" stroke="currentColor" fill="none" stroke-width="15" stroke-dasharray="114" ></path>	
        </svg>
        <svg viewBox="0 0 110 110" class="text-blue w-32" v-else>
            <circle id="loader-circle" cx="55" cy="55" r="50" stroke-dasharray="314.16" stroke="currentColor" fill="none" stroke-width="10"></circle>	
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
                repeatCount="indefinite"/>
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
            Click <button @click="redirect" class="font-normal text-blue hover:undeline">here</button> to redirect manually.
        </p>
    </div>
</template>

<script>
import CountdownTimer from './CountdownTimer.vue';
import QuizQuestion from './QuizQuestion.vue';
    export default {
        components: {CountdownTimer, QuizQuestion},
        props: {
            action: {required: true},
            redirectTo: {required: true},
            dataQuestions: {default: []},
            timeLimit: {default: 30},
        },
        data() {
            return {
                isLive: true,
                submission: {
                    done: false,
                    success: false,
                    text: 'Please wait! While we record your response.',
                },
                hurry: false,
                currentQuestionIndex: 0,
                responses: [],
                questions: []
            }
        },
        computed: {
            currentQuestion() {
                return this.questions[this.currentQuestionIndex];
            },
            currentResponse() {
                return this.responses[this.currentQuestionIndex];
            },
            isFirstQuestion() {
                return this.currentQuestionIndex === 0;
            },
            isLastQuestion() {
                return this.currentQuestionIndex === this.questions.length-1;
            }
        },
        methods: {
            isCurrentQuestion(index) {
                return this.currentQuestionIndex == index;
            },
            isQuestionAnswered(index) {
                return !this.isCurrentQuestion(index) &&
                    this.questions[index].visited &&
                    this.responses[index].length;
            },
            isQuestionSkipped(index) {
                return !this.isCurrentQuestion(index) &&
                    this.questions[index].visited &&
                    !this.responses[index].length;
            },
            setCurrentQuestion(index) {
                if (index >= 0 && index < this.questions.length ) {
                    this.questions[this.currentQuestionIndex].visited = true
                    this.currentQuestionIndex = index
                }
            },
            nextQuestion() {
                this.setCurrentQuestion(this.currentQuestionIndex + 1);
            },
            previousQuestion() {
                this.setCurrentQuestion(this.currentQuestionIndex - 1);
            },
            selectResponse(response) {
                console.log(response);
                this.responses.splice(this.currentQuestionIndex, 1, response);
            },
            submit() {
                if(confirm('You still have time left. Are you sure you want to submit your Response?')) {
                    this.endQuiz();
                }
            },
            endQuiz() {
                this.isLive = false;
                this.sendResponses();
            },
            sendResponses() {
                    const responses = this.getResponses();
                    axios.post(this.action, {responses})
                        .catch(this.onFailure)
                        .then(this.onSuccess);
            },
            getResponses() {
                return this.responses.filter(answers => !!answers.length)
                    .map(answers => {
                        return {
                            question_id: answers[0].question_id, 
                            response_key: answers.map(answer => answer.key).join(':')
                        };
                    });
            },
            onSuccess({data}) {
                this.submission = {
                    done: true,
                    success: data.message.level == 'success',
                    text: data.message.message,
                }
                setTimeout(this.redirect, 3*1000);
            },
            redirect() {
                window.location.replace(this.redirectTo);
            },
            onFailure({response}) {
                this.submission = {
                    done: true,
                    success: response.data.message.level != 'danger',
                    text: response.data.message.message,
                }
            }
        },
        created() {
            this.responses = new Array(this.dataQuestions.length).fill([]);
            this.questions = this.dataQuestions.map(question => {
                question.visited = false;
                return question;
            })
        }
    }
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
