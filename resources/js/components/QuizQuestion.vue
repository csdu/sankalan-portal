<template>
    <div class="question outline-none">
        <div class="card px-3 pt-3 pb-6 relative overflow-hidden">
            <strong class="float-left mr-2" v-text="`Q${index+1}.`"></strong>
            <p v-html="dataQuestion.text"></p>
        </div>
        <pre v-if="dataQuestion.code" 
        class="card p-4 font-mono my-4 leading-normal tracking-wide whitespace-pre-line" v-text="escapedCode"></pre>
        <div class="flex justify-center my-4" v-if="dataQuestion.illustration">
            <img :src="dataQuestion.illustration" class="max-w-full rounded shadow-lg">
        </div>
        <ul class="choices-list list-reset my-8 flex flex-wrap -mx-2" v-if="choicesCount">
            <li v-for="(choice, choiceIndex) in dataQuestion.choices" 
                :key="choice.id" 
                class="mb-3 w-full md:w-1/2 px-2">
                <label :for="`choice-${choice.key}`"
                    class="relative flex items-center btn hover:bg-grey-light border shadow cursor-pointer pl-6 h-full"
                    @mouseover="highlightResponse(choiceIndex)"
                    :class="{
                        'bg-white': !isHighlighted(choiceIndex) && !isSelected(choiceIndex),
                        'bg-green-dark': isHighlighted(choiceIndex) && isSelected(choiceIndex),
                        'bg-green': !isHighlighted(choiceIndex) && isSelected(choiceIndex),
                        'bg-grey-light border border-grey-darker': isHighlighted(choiceIndex),
                        'text-white hover:bg-green-dark border-green-dark': isSelected(choiceIndex),
                    }">
                    <div v-if="isHighlighted(choiceIndex)"
                    class="absolute ml-3 pin-l pin-y flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full" 
                        :class="isSelected(choiceIndex) ? 'bg-white' : 'bg-green'"></span>
                    </div>
                    <input :id="`choice-${choice.key}`" 
                        type="radio" 
                        class="hidden"
                        :name="`question-${choice.question_id}`" 
                        @input="toggleResponse(choiceIndex)"
                        :value="choice.key">
                    <div>
                    <img v-if="choice.illustration" :src="choice.illustration" :alt="choice.text" class="rounded my-2 max-w-full">
                    <pre v-if="choice.code" v-html="choice.code" class="my-2"></pre>
                    <p class="ml-1" v-html="choice.text"></p>
                    </div>
                </label>
            </li>
        </ul>
        <!-- Input Answer -->
        <div class="card my-4 p-4" v-else>
            <div class="flex" v-if="editing">
                <input ref="input" type="text" class="flex-1 mr-1 control" v-model="answer" autofocus @keydown.enter.prevent.stop="saveResponse">
                <button @click="saveResponse" class="btn btn-green is-sm">Save</button>
            </div>
            <div class="flex" v-else>
                <p class="flex-1 mr-1" :class="{'text-grey': !answer}" v-text="answer || 'No answer'"></p>
                <button @click="editResponse" class="btn is-blue is-sm">Edit</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        dataQuestion: {required: true},
        index: {required: true},
        value: {default: null},
    },
    data() {
        return {
            highlightedResponseIndex: 0,
            editing: false,
            answer: null,
            keyEvents: {
                'ArrowDown': () => this.highlightNextResponse(),
                'ArrowUp': () => this.highlightPreviousResponse(),
                'Space': () => this.toggleResponse(this.highlightedResponseIndex),
                'Delete': () => this.clearResponse(),
                'Backspace': () => this.clearResponse(),
            }
        }
    },
    computed: {
        choicesCount() {
            return this.dataQuestion.choices.length;
        },
        escapedCode() {
            if(this.dataQuestion.code) {
                return this.dataQuestion.code.replace(/\n/g, '\\n').replace(/<br>/g, "\n");
            }
            return null;
        }
    },
    methods: {
        highlightResponse(index) {
            this.highlightedResponseIndex = index;
        },
        highlightNextResponse() {
            this.highlightedResponseIndex = (this.highlightedResponseIndex + 1) % this.choicesCount
        },
        highlightPreviousResponse() {
            this.highlightedResponseIndex = this.highlightedResponseIndex <= 0 ? 
                (this.choicesCount - 1) 
                : (this.highlightedResponseIndex - 1)
        },
        toggleResponse(index) {
            if(this.isSelected(index)) {
               this.$emit('input', ''); 
            } else {
                this.$emit('input', this.dataQuestion.choices[index].key);
            }
        },
        isSelected(index) {
            const choice = this.dataQuestion.choices[index];
            return choice && this.value == choice.key;
        },
        isHighlighted(index) {
            return this.highlightedResponseIndex == index;
        },
        clearResponse() {
            this.$emit('input', '');
        },
        editResponse() {
            this.editing = true;
            this.$nextTick(() => this.$refs.input.focus());
        },
        saveResponse() {
            this.editing = false;
            this.$emit('input', this.answer);
        },
    },
    watch:{
        value() {
            this.answer = this.value || '';
        }
    },
    mounted() {
        if(this.dataQuestion.choices.length > 0) {
            window.addEventListener('keydown', ({code}) => {
                if(this.keyEvents.hasOwnProperty(code)) {
                    this.keyEvents[code]();
                    return false;
                }
            });
        }
    }
}
</script>

