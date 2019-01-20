<template>
    <div class="question outline-none" tabindex="1"
    @keydown.down="selectNextResponse"
    @keydown.up="selectPreviousResponse"
    @keydown.backspace="clearResponse"
    @keydown.delete="clearResponse">
        <strong class="float-left mr-2" v-text="`Q${index+1}.`"></strong>
        <p v-html="dataQuestion.question"></p>
        <div class="flex">
            <ul class="options-list list-reset mt-4 flex-1">
                <li v-for="option in dataQuestion.options" :key="option.value" class="mb-3">
                    <label :for="`option-${option.value}`"
                        class="flex items-center bg-grey-light border p-2 rounded cursor-pointer hover:bg-grey"
                        :class="{'text-white bg-green border-green-dark hover:bg-green-dark': isSelected(option.value)}">
                        <input :id="`option-${option.value}`" 
                        type="radio" 
                        class="hidden"
                        :name="`question-${index + 1}`" 
                        @change="selectResponse(index)"
                        :value="option.value">
                        <span class="ml-1" v-text="option.text"></span>
                    </label>
                </li>
            </ul>
            <div class="w-64 flex justify-center items-center">
                <button class="px-4 py-1 rounded bg-grey-light text-black" @click="clearResponse"> 
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
        optionsCount() {
            return this.dataQuestion.options.length;
        }
    },
    methods: {
        selectNextResponse() {
            this.selectResponse( 
                (this.selectedResponseIndex + 1) % this.optionsCount
            );
        },
        selectPreviousResponse() {
            this.selectResponse(
                this.selectedResponseIndex <= 0 ? 
                    (this.optionsCount - 1) 
                    : (this.selectedResponseIndex - 1)
            );
        },
        selectResponse(index) {
            this.selectedResponseIndex = index;
            const response = index == -1 ? null : this.dataQuestion.options[this.selectedResponseIndex].value;
            this.$emit('input',  response);
        },
        isSelected(value) {
            return this.value == value;
        },
        clearResponse() {
            this.selectResponse(-1);
        }
    },
    mounted() {
    }
}
</script>

