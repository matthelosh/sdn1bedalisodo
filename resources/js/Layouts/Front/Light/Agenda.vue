<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';
import { Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/style.css';

const date = ref(new Date())
</script>

<template>
    <div class="w-full bg-white py-8">
        <div class="container w-10/12 md:w-8/12 mx-auto">
            <div class="header w-full my-4">
                <h1 class="flex justify-center items-center gap-1 text-xl font-bold text-sky-950 tracking-wide">
                    <Icon icon="mdi:calendar-badge-outline" class="text-2xl text-sky-800" />
                    Agenda Sekolah
                </h1>
            </div>
            <div class="content grid grid-cols-4  gap-2 md:gap-20 mt-6">
                <Calendar class="cols-span-4 md:col-span-1 mx-auto" />
                <!-- <DatePicker v-model="date" /> -->
                <ol class="relative border-l border-gray-200 dark:border-gray-700 col-span-4 md:col-span-3">                  
                    <li class="mb-10 ml-4">
                        <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">February 2022</time>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application UI code in Tailwind CSS</h3>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Get access to over 20+ pages including a dashboard layout, charts, kanban board, calendar, and pre-order E-commerce & Marketing pages.</p>
                        <a href="#" class="inline-flex items-start px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-200 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            
                            Learn more 
                            <Icon icon="mdi:arrow-right-thin" class="text-2xl" />
                        </a>
                    </li>
                    <li class="mb-10 ml-4">
                        <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">March 2022</time>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Marketing UI design in Figma</h3>
                        <p class="text-base font-normal text-gray-500 dark:text-gray-400">All of the pages and components are first designed in Figma and we keep a parity between the two versions even as we update the project.</p>
                    </li>
                    <li class="ml-4">
                        <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">April 2022</time>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">E-Commerce UI code in Tailwind CSS</h3>
                        <p class="text-base font-normal text-gray-500 dark:text-gray-400">Get started with dozens of web components and interactive elements built on top of Tailwind CSS.</p>
                    </li>
                </ol>
            </div>
            
            

        </div>
    </div>
</template>