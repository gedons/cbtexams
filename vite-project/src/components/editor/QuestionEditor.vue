<template>
    <!-- Question index -->
    <div class="flex items-center justify-between font-sans">
        <h3 class="text-lg font-bold">
            {{ index + 1 }}. {{ model.question }}
        </h3>

        <div class="flex items-center">
            <!-- add new question -->
            <button type="button" @click="addQuestion()" class="
                            flex
                            items-center
                            text-xs
                            py-1
                            px-3
                            mr-2
                            rounded-sm
                            text-white
                            bg-gray-600
                            hover:bg-gray-700
                            font-sans
                        ">
                Add
            </button>
            <!-- add new question -->


            <!-- delete question -->
            <button type="button" @click="deleteQuestion()" class="
                            flex
                            items-center
                            text-xs
                            py-1
                            px-3
                            mr-2
                            rounded-sm
                            text-white
                            border border-transparent
                            bg-red-500
                            hover:border-red-600
                            font-sans
                        ">
                Delete
            </button>
            <!-- delete question -->
        </div>
    </div>
    <!-- /Question index -->

    <div class="grid gap-3 grid-cols-12 font-sans">
        <!-- Question -->
        <div class="mt-3 col-span-9">
            <label :for="'question_text' + model.data" class="block text-sm font-meduim text-gray-700">Question Text
            </label>
            <input type="text" :name="'question_text_' + model.data" v-model="model.question" @change="dataChange"
                :id="'question_text_' + model.data" class="
                        mt-1
                        focus:ring-indigo-500
                        focus:border-indigo-500
                        block
                        w-full
                        shadow-sm
                        sm:text-sm
                        border-gray-300
                        rounded-md
                     " />
        </div>
        <!-- /Question -->

        <!-- Question type -->
        <div class="mt-3 col-span-3">
            <label for="question_type" class="block text-sm font-meduim text-gray-700">Select Question Type</label>
            <select name="question_type" id="question_type" v-model="model.type" @change="typeChange()" class="
                            mt-1
                            py-2
                            px-3
                            bg-white
                            focus:ring-indigo-500
                            focus:border-indigo-500
                            focus:outline-none
                            block
                            w-full
                            shadow-sm
                            sm:text-sm
                            border-gray-300
                            rounded-md
                         ">
                <option v-for="type in questionTypes" :key="type" :value="type">
                    {{ upperCaseFirst(type) }}
                </option>
            </select>
        </div>
        <!-- /Question type -->
    </div>

    <div>
        <!-- Question Description -->
        <div class="mt-3 col-span-9 font-sans">
            <label :for="'question_description_' + model.id" class="block text-sm font-meduim text-gray-700">
                Description</label>
            <textarea :name="'question_description_' + model.id" v-model="model.description" @change="dataChange()"
                :id="'question_description_' + model.id" class="
                            mt-1
                            focus:ring-indigo-500
                            focus:border-indigo-500
                            block
                            w-full
                            shadow-sm
                            sm:text-sm
                            border-gray-300
                            rounded-md
                        " />
        </div>
        <!-- /Question Description -->

        <!-- Question Data -->
        <div class="font-sans">
            <div v-if="shouldHaveOption()" class="mt-2">
                <h4 class="text-sm font-semibold mb-1 flex justify-between items-center">
                    Options

                    <!-- add new option -->
                    <button
                        type="button"
                        @click="addOption()"
                        class="
                           flex
                            items-center
                            text-xs
                            py-1
                            px-3
                            mr-2
                            rounded-sm
                            text-white
                            bg-gray-600
                            hover:bg-gray-700
                           
                        "
                    >
                    Add Option
                    </button>
                    <!-- /add new option -->
                </h4>

                <div 
                    v-if="!model.data.options.length"
                    class="text-xs text-gray-600 text-center py-3"
                >
                    You don't have any option defined
                </div>
                <!-- option list -->
                <div
                    v-for="(option, index) in model.data.options"
                    :key="option.uuid"
                    class="flex items-center mb-1"
                >
                    <span class="w-6 text-sm">{{index + 1}}.</span>
                    <input 
                        type="text"
                        v-model="option.text"
                        @change="dataChange()"
                        class="
                            w-full
                            rounded-sm
                            py-1
                            px-2
                            text-xs
                            border border-gray-300
                            focus:border-indigo-500
                        "
                    />
                    <!-- delete option -->
                     <button
                        type="button"
                        @click="removeOption(option)"
                        class="
                            rounded-full
                            flex
                            items-center
                            justify-center
                            h-6
                            w-6
                            text-red-500
                            border border-transparent
                            transition-colors
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    <!-- /delete option -->
                </div>
                <!-- /option list -->
            </div>
        </div>
        <!-- /Question Data -->
        <hr class="my-4" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import {v4 as uuidv4} from "uuid";
import store from '../../store';



const props = defineProps({
    question: Object,
    index: Number,
});

const emit = defineEmits(["change", "addQuestion", "deleteQuestion"]);

const model = ref(JSON.parse(JSON.stringify(props.question)));

//get the question types
const questionTypes = computed(() => store.state.questionTypes);

//functions
function upperCaseFirst(str){
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function shouldHaveOption() {
    return ["select", "radio", "checkbox"].includes(model.value.type);

}

function getOptions() {
    return model.value.data.options;
}

function setOptions(options) {
     model.value.data.options = options;
}

function addOption() {
    setOptions([
        ...getOptions(),
        {uuid: uuidv4(), text: ""},
    ]);
    dataChange();
}

function removeOption(op) {
    setOptions(getOptions().filter((opt) => opt !== op));
    dataChange();
}

function typeChange() {
    if (shouldHaveOption()) {
        setOptions(getOptions() || []);
    }
    dataChange(); 
}

function dataChange() {
    const data = JSON.parse(JSON.stringify(model.value));
    if (!shouldHaveOption()) {
        delete data.data.options;
    }

    emit("change", data);
}
 function addQuestion(){
    emit("addQuestion", props.index + 1);
 }

 function deleteQuestion(){
    emit("deleteQuestion", props.question);
 }
</script>

<style>
</style>