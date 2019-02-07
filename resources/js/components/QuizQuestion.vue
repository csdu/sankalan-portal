<template>
    <div class="question outline-none" tabindex="1"
        @keydown.down.prevent="highlightNextResponse"
        @keydown.up.prevent="highlightPreviousResponse"
        @keydown.space.prevent="toggleResponse(highlightedResponseIndex)"
        @keydown.delete.prevent="clearResponse">
        <div class="card px-3 pt-3 pb-6 relative overflow-hidden">
            <span v-if="dataQuestion.is_multiple"
                class="absolute pin-r pin-b p-1 bg-blue-light text-white text-xs">
                Multiple
            </span>
            <strong class="float-left mr-2" v-text="`Q${index+1}.`"></strong>
            <p v-html="dataQuestion.text"></p>
        </div>
        <pre v-if="dataQuestion.code" 
        class="card p-4 font-mono my-4 leading-normal tracking-wide whitespace-pre-line" v-text="escapedCode"></pre>
        <div class="flex justify-center my-4" v-if="dataQuestion.illustration">
            <img :src="dataQuestion.illustration" class="max-w-full rounded shadow-lg">
        </div>
        <ul class="choices-list list-reset my-8 flex flex-wrap -mx-2">
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
                        :type="dataQuestion.is_multiple ? 'checkbox' : 'radio'" 
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
            highlightedResponseIndex: 0
        }
    },
    computed: {
        choicesCount() {
            return this.dataQuestion.choices.length;
        },
        escapedCode() {
            return this.dataQuestion.code.replace(/\n/g, '\\n').replace(/<br>/g, "\n");
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
        removeResponse(index) {
            return this.value.filter((_, itemIndex) => index != itemIndex);
        },
        toggleResponse(index) {
            const choice = this.dataQuestion.choices[index];
            index = this.value.findIndex(orgChoice => orgChoice.key == choice.key);

            if(index != -1) {
                this.$emit('input', this.removeResponse(index));
            } else if (this.dataQuestion.is_multiple) {
                this.$emit('input',  this.value.concat([choice]));
            } else {
                this.$emit('input', [choice]);
            }
        },
        isSelected(index) {
            const choice = this.dataQuestion.choices[index];
            return !!this.value.find(orgChoice => orgChoice.key == choice.key)
        },
        isHighlighted(index) {
            return this.highlightedResponseIndex == index;
        },
        clearResponse() {
            this.$emit('input', []);
        }
    },
    mounted() {
        this.$el.focus();
    }
}
</script>

