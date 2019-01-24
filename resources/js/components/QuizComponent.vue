<template>
    <div class="flex h-full" v-if="isLive" 
            @keydown.right="nextQuestion"
            @keydown.left="previousQuestion"
            @keydown.enter="submit">
        <div class="question-area w-3/4 h-full border-r px-4 overflow-auto">
            <h1 class="my-8 text-center">Event Quiz Title</h1>
            <quiz-question 
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
        <div class="navigation flex flex-col h-full w-1/4 px-3 overflow-auto">
            <ul class="questions-nav list-reset flex flex-wrap -m-1">
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
            class="inline-flex justify-center my-8 text-lg font-bold font-mono" 
            :class="{'animation-vibrate text-red': hurry}"
            @timeup="endQuiz" @hurryup="hurry=true"></countdown-timer>
            <div class="mt-auto mb-8 text-center">
                <button class="btn is-green" @click.prevent="submit">Submit</button>
            </div>
        </div>
    </div>
    <div class="fixed pin-x pin-t z-50 bg-white h-screen flex flex-col justify-center items-center" v-else>
        <svg viewBox="0 0 100 100" class="text-green w-32" v-if="submitted">
            <path d="M10 50 L40 80 L90 10" stroke="currentColor" fill="none" stroke-width="15"></path>	
        </svg>	
        <svg viewBox="0 0 110 110" class="text-green w-32 mb-4" v-else>
            <circle id="loader-circle" cx="55" cy="55" r="50" stroke-dasharray="290" stroke-dashoffset="290" stroke="currentColor" fill="none" stroke-width="10"></circle>	
            <animate 
                xlink:href="#loader-circle"
                attributeName="stroke-dashoffset"
                attributeType="XML"
                from="290"
                to="290" 
                dur="0.8s"
                begin="0s"
                keyTimes="0; 0.5; 1;"
                values="290; 0; 290;"
                repeatCount="indefinite"/>
            <animateTransform 
                xlink:href="#loader-circle"
                attributeName="transform" 
                attributeType="XML"
                type="rotate"
                from="0 55 55"
                to="360 55 55" 
                dur="1s"
                begin="0s"
                repeatCount="indefinite"
                />
        </svg>	
        <p class="text-center" v-text="submissionStatus"></p>
    </div>
</template>

<script>
import CountdownTimer from './CountdownTimer.vue';
import QuizQuestion from './QuizQuestion.vue';
    export default {
        components: {CountdownTimer, QuizQuestion},
        props: {
            dataQuestions: {default: []},
            timeLimit: {default: 30},
        },
        data() {
            return {
                isLive: true,
                submitted: false,
                hurry: false,
                submissionStatus: 'Please wait! While we submit your Response.',
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
                return !this.isCurrentQuestion(index) && this.questions[index].visited && this.responses[index] != null;
            },
            isQuestionSkipped(index) {
                return !this.isCurrentQuestion(index) && this.questions[index].visited && this.responses[index] == null;
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
                this.sendResponses().then(this.postSubmitCallback);
            },
            postSubmitCallback() {
                this.submitted = true;
                this.submissionStatus = "Your response has been submitted! Thank you for participation. Result's will be announced soon.";
            },
            sendResponses() {
                return new Promise((resolve, reject) => setTimeout(() => resolve(true), 2500))
                // alert('Your Response has been submitted! Thank you for your participation.');
            }
        },
        created() {
            this.responses = new Array(this.dataQuestions.length).fill(null);
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
