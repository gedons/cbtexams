<template>
  <PageComponent>
             <template v-slot:header>
                <div class="flex font-sans">
                    <button type="button" onclick="window.close()" class="flex text-sm border border-2 border-transparent p-0 py-2 px-4 rounded-md text-white bg-indigo-600">Close</button>
                </div>
            </template>

              <div class="font-sans bg-white animate-fade-in-down shadow-md p-3 text-center flex flex-col order-1 lg:order-2" style="animation-delay: 0.1s">
                    <h3 class="text-2xl font-semibold uppercase">Exam Not Started or Expired</h3>
                    <div
                        class="text-8xl font-semibold flex-1 flex items-center justify-center">
                </div>
            </div>
  </PageComponent>
</template>

<script setup>
 import PageComponent from '../components/PageComponent.vue';

</script>

<style>
</style>