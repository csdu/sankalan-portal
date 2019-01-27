<template>
    <div class="question outline-none" tabindex="1"
    @keydown.down="selectNextResponse"
    @keydown.up="selectPreviousResponse"
    @keydown.backspace="clearResponse"
    @keydown.delete="clearResponse">
        <strong class="float-left mr-2" v-text="`Q${index+1}.`"></strong>
        <p v-html="dataQuestion.text"></p>
        <div class="flex">
            <ul class="choices-list list-reset mt-4 flex-1">
                <li v-for="(choice, choiceIndex) in dataQuestion.choices" :key="choice.id" class="mb-3">
                    <label :for="`choice-${choice.key}`"
                        class="flex items-center btn"
                        :class="{'is-green': isSelected(choiceIndex)}">
                        <input :id="`choice-${choice.key}`" 
                        type="radio" 
                        class="hidden"
                        :name="`question-${choice.question_id}`" 
                        @input="selectResponse(choiceIndex)"
                        :value="choice.value">
                        <span class="ml-1" v-text="choice.text"></span>
                    </label>
                </li>
            </ul>
            <div class="w-64 flex justify-center items-center">
                <button class="btn" @click="clearResponse"> 
                    <span class="font-bold text-red text-lg">&times;</span> Clear
                </button>
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
            selectedResponseIndex: -1
        }
    },
    computed: {
        choicesCount() {
            return this.dataQuestion.choices.length;
        }
    },
    watch: {
        value() {
            this.selectedResponseIndex = this.indexOfResponse(this.value);
        }
    },
    methods: {
        indexOfResponse(value) {
            if(!value) {
                return -1;
            }
            return this.dataQuestion.choices.findIndex(choice => choice.key == value.key);
        },
        selectNextResponse() {
            this.selectResponse( 
                (this.selectedResponseIndex + 1) % this.choicesCount
            );
        },
        selectPreviousResponse() {
            this.selectResponse(
                this.selectedResponseIndex <= 0 ? 
                    (this.choicesCount - 1) 
                    : (this.selectedResponseIndex - 1)
            );
        },
        selectResponse(index) {
            console.log(index);
            this.selectedResponseIndex = index;
            const response = index == -1 ? null : this.dataQuestion.choices[this.selectedResponseIndex];
            this.$emit('input',  response);
        },
        isSelected(index) {
            return this.selectedResponseIndex == index;
        },
        clearResponse() {
            this.selectResponse(-1);
        }
    }
}
</script>

