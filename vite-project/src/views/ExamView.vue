
    <template>
        <PageComponent>
            <template v-slot:header>
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{route.params.id ? model.title: "Create an Exams"}}
                    </h1>
                    <div class="flex">
                     <a
                        v-if="model.slug"
                        :href="`/view/exam/${model.slug}`"
                        target="_blank"
                        type="button"
                        class="flex text-sm border border-2 border-transparent p-0 py-2 px-4 rounded-md text-indigo-500 focus:border-indigo-500 mr-2"
                    >
                     <ExternalLinkIcon class="w-5 h-5" />
                        View Exam
                    </a>
                    <button
                        v-if="route.params.id"
                        type="button"
                        @click="deleteExam()"
                        class="flex text-sm border border-2 border-transparent p-0 py-2 px-4 rounded-md bg-red-500 text-white hover:bg-red"
                    >
                    <TrashIcon class="w-5 h-5 mr-2" />
                        Delete Exam
                    </button>
                    </div>
                    
                </div>
            </template>
           <div v-if="examLoading"  role="status" class="flex justify-center">
                <svg aria-hidden="true" class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
           
            <form v-else @submit.prevent="saveExam" class="animate-fade-in-down">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                   <!-- exam end -->
                   <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- image start -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Image
                                    </label>
                                </div>
                                
                                <div class="mt-1 flex items-center">
                                    <img 
                                        v-if="model.image_url"
                                        :src="model.image_url" 
                                        :alt="model.title"
                                        class="w-64 h-48 object-cover"   
                                    />
                                    <span
                                        v-else
                                        class="
                                            items-center
                                            justify-center
                                            h-12
                                            w-12
                                            rounded-full
                                            overflow-hidden
                                            bg-gray-100
                                        "
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>

                                    </span>
                                    <button
                                        type="button"
                                        class="
                                            relative
                                            overflow-hidden
                                            ml-5
                                            bg-white
                                            py-2
                                            px-3
                                            border border-gray-300
                                            rounded-md
                                            shadow-sm
                                            text-sm
                                            leading-4
                                            font-meduim
                                            text-gray-700
                                            hover:bg-gray-50
                                            focus:outline-none
                                            focus:ring-2
                                            focus:ring-offset-2
                                            focus:ring-indigo-500
                                        "
                                    >
                                    <input 
                                        type="file"
                                        @change="onImageChoose"
                                        class="
                                            absolute
                                            left-0
                                            top-0
                                            right-0
                                            bottom-0
                                            opacity-0
                                            cursor-pointer
                                        "
                                        />
                                        change
                                    </button>
                                </div>
                            </div>
                        <!-- image end -->

                        <!-- title start -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class=" block text-sm font-bold text-gray-700">
                                        Title
                                    </label>
                                </div>
                                

                                <input 
                                    type="text"
                                    name="title"
                                    id="title"
                                    v-model="model.title"
                                    autocomplete="exam_title"
                                    class="
                                        mt-1
                                        focus:ring-indigo-500 focus:border-indigo-500
                                        block
                                        w-full
                                        shadow-sm
                                        sm:text-sm
                                        border-gray-300
                                        rounded-md
                                    "    
                                />
                            </div>
                        <!-- title end -->

                        <!-- description start -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Description
                                    </label>
                                </div>
                                <div class="mt-1">
                                    <textarea   
                                        name="description"
                                        id="description" 
                                        cols="30" 
                                        rows="3" 
                                        v-model="model.description"
                                        autocomplete="exam_description"
                                        class="
                                            shadow-sm
                                            focus:ring-indigo-500 focus:boder-indigo-500
                                            mt-1
                                            block
                                            w-full
                                            sm:text-sm
                                            border border-gray-300
                                            rounded-md
                                        "
                                        placeholder="Describe the exam"
                                    />
                                </div>
                            </div>
                        <!-- description end -->

                        <!-- expire date start -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Expire Date
                                    </label>
                                </div>
                                <input 
                                        type="date"
                                        name="expire_date"
                                        id="expire_date"
                                        v-model="model.expire_date"
                                        class="
                                            mt-1
                                            focus:ring-indigo-500 focus:border-indigo-500
                                            block
                                            w-full
                                            shadow-sm
                                            sm:text-sm
                                            border-gray-300
                                            rounded-md
                                        "    
                                    />
                            </div>

                        <!-- expire date end -->

                        <!-- status start -->
                               <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input 
                                            id="status"
                                            name="status"
                                            type="checkbox"
                                            v-model="model.status"
                                            class="
                                                focus:ring-indigo-500
                                                h-4
                                                w-4
                                                text-indigo-600
                                                border-gray-300
                                                rounded
                                            "
                                            />
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="status" class="font-medium text-gray-700">
                                            Active
                                        </label>
                                    </div>
                               </div>
                        <!-- status end -->
                    </div>
                    <!-- exam end -->
                    
                    <!-- question start -->
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <h3 class="text-2xl font-semibold flex items-center justify-between">
                                Questions
                                <!-- add new question -->
                                    <button
                                        type="button"
                                        @click="addQuestion()"
                                        class="
                                            flex
                                            items-center
                                            text-sm
                                            py-1
                                            px-4
                                            rounded-sm
                                            text-white
                                            bg-gray-600
                                            hover:bg-gray-700
                                        "
                                    >
                                        Add Question
                                    </button> 
                                <!-- add new question end -->
                            </h3>

                            <div v-if="!model.questions.length" class="text-center text-gray-600">
                                No Questions Created Yet
                            </div>
                            <div v-for="(question, index) in model.questions" :key="question.id">
                                <QuestionEditor
                                    :question="question"
                                    :index="index"
                                    @change="questionChange"
                                    @addQuestion="addQuestion"
                                    @deleteQuestion="deleteQuestion"
                                />
                            </div>
                        </div>
                    <!-- question end -->

                    <!-- save  button -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button 
                            type="submit"
                            class=" inline-flex
                                    justify-center
                                    py-2
                                    px-3
                                    border border-transparent
                                    rounded-md
                                    shadow-sm
                                    text-sm
                                    text-white
                                    font-meduim
                                    bg-indigo-700
                                    hover:bg-indigo-700
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-offset-2
                                    focus:ring-indigo-500"
                            >
                             <SaveIcon class="w-5 h-5 mr-1" />
                            Save
                        </button>
                    </div>
                    <!-- save button end -->
                </div>
            </form>
        </PageComponent>
    </template>


    <script setup>
        import { ref, watch, computed } from 'vue';
        import store from '../store';
        import { useRoute , useRouter} from 'vue-router';
        import {v4 as uuidv4} from 'uuid';
        import PageComponent from '../components/PageComponent.vue';
        import QuestionEditor from '../components/editor/QuestionEditor.vue';
        import { SaveIcon, TrashIcon, ExternalLinkIcon } from '@heroicons/vue/solid'

        const route = useRoute();
        const router = useRouter();
        const examLoading = computed(() => store.state.currentExam.loading);

        let model = ref({
            title: "",
            slug: "",
            status: false,
            description: null,
            image_url: null,
            expire_date: null,
            questions: [],
        });

        watch(
            () => store.state.currentExam.data,
            (newVal, oldVal) => {
                model.value = {
                    ...JSON.parse(JSON.stringify(newVal)),
                     status: !!newVal.status,
                    //  status: newVal.status !== "draft",
                };
            }
        );

        // if id is present then prepare for editupdate
        if (route.params.id) {
            store.dispatch('getExam', route.params.id); 
        }

        function onImageChoose(ev) {
            const file = ev.target.files[0];

            const reader = new FileReader();
            reader.onload = () => {
                model.value.image = reader.result;
                model.value.image_url = reader.result;
            };
            reader.readAsDataURL(file);
        }

        function addQuestion(index){
            const newQuestion = {
                id: uuidv4(),
                type: "text",
                question: "",
                description: null,
                data: {},
            };
            model.value.questions.splice(index, 0, newQuestion);
        }

        function deleteQuestion(question) {
            model.value.questions = model.value.questions.filter(
                (q) => q !== question
            );
        }

        function questionChange(question) {
            model.value.questions = model.value.questions.map(
                (q) => {
                    if (q.id === question.id) {
                        return JSON.parse(JSON.stringify(question));
                    }
                    return q; 
                }
            );
        }

        function saveExam(){
            store.dispatch("saveExam", model.value).then(({data}) => {
                store.commit('notify', {
                    type: 'success',
                    message: 'Exams was successfully updated'
                });
               router.push({
                    name: "ExamView",
                    params: {id: data.data.id},
                });
            });
        }

        function deleteExam(){
            if(confirm('Are you sure? Operation can`t be undone')){
                store.dispatch("deleteExam", model.value.id)
                .then(() => {
                    store.commit('notify', {
                    type: 'error',
                        message: 'Exams was successfully Deleted'
                    });
                    router.push({
                        name: "Exams",
                    });
                });
            }
        }
    
    </script>


    <style scoped>
    
    </style>