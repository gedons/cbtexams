<template>
     <div 
        class="
            py-2
            px-3
            shadow-md
            bg-white
            hover:bg-gray-50
            h-[470px]
        "
               >
               <img :src="exam.image_url" alt="web-image" class="w-full h-48 object-cover"/>
               <h4 class="mt-4 text-lg font-bold">{{exam.title}}</h4>
               <div v-html="exam.description" class="overflow-hidden flex-1"></div>
                <div class="flex items-center justify-between mt-3">
                    <router-link 
                        :to="{ name: 'ExamView', params: {id: exam.id} }"
                        class="
                            py-2
                            px-4
                            border border-transparent
                            text-sm
                            text-white
                            bg-indigo-600
                            rounded-md
                            hover:bg-indigo-700
                            focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                        "> 
                        <!-- svg plus goes here -->
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        
                    </router-link>
                    <div class="flex items-center">
                        <a 
                          :href="`/view/exam/${exam.slug}`"
                          target="_blank"
                          class="h-8 w-8 flex items-center justify-center rounded-full
                          border border-transparent text-sm text-indigo-500 focus:ring-2
                          focus:ring-offset-2 focus:ring-indigo-500"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <button
                            v-if="exam.id"
                            type="button"
                            @click="emit('delete', exam)"
                            class="
                            h-8
                            w-8
                            flex
                            item-center
                            justify-center
                            border border-transparent
                            text-sm text-red-500
                            rounded-full
                            focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                       ">
                            <!-- Svg trash goes here -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
        </div> 
</template>

<script setup>
    const {exam} = defineProps({
        exam: Object,
    });
    const emit = defineEmits(['delete']); 
</script>

<style></style>