<script setup>
import KataCard from "../../Components/KataCard.vue";
import KataFilter from "../../Components/KataFilter.vue";
import axios from "axios";

import {onMounted} from 'vue';

let props = defineProps({
    katas: Object,
    sort: Object,
    tags: Object,
    langs: Object,
    katasPassedLangs: Object,
});

onMounted(() => {
    window.onscroll = () => {
        let scrollHeight = Math.max(
            document.body.scrollHeight, document.documentElement.scrollHeight,
            document.body.offsetHeight, document.documentElement.offsetHeight,
            document.body.clientHeight, document.documentElement.clientHeight,
        );

        let bottomOfWindow = window.pageYOffset + window.innerHeight === scrollHeight;

        // TODO stop loading when all katas are loaded
        if (bottomOfWindow) {
            axios.get('/' + '?from=' + props.katas.data.length + '&' + window.location.search.substring(1)).then(response => {
                response.data.forEach((nextKata) => props.katas.data.push(nextKata));
            });
        }
    }
});

</script>

<template>
    <Head title="Kata Practice"/>

    <ul class="flex flex-row items-center justify-center space-x-4 text-sm mb-2">
        <li class="flex items-center justify-center h-10 border-b border-red-700">
            <a class="pointer-events-none cursor-default">
                <i class="icon-moon-compare "></i> Library
            </a>
        </li>
        <li class="flex items-center justify-center h-10 border-b border-transparent hover:text-ui-link-hover">
            <a href="#">
                <i class="icon-moon-collection "></i> Collections
            </a>
        </li>
    </ul>

    <section class="items-list flex flex-col md:flex-row max-w-screen-2xl mx-auto">
        <div class="filters px-0 w-full md:w-3/12 max-w-lg mx-auto bg-ui-section rounded-lg" style="height: fit-content">
            <KataFilter :sort="sort" :langs="langs" :tags="tags"></KataFilter>
        </div>
        <div class="katas w-full md:w-9/12 pl-4 space-y-2">
            <span class="text-sm">{{ katas.total }} Kata Found</span>
            <KataCard v-for="kata in katas.data" :key="kata.id" :kata="kata"></KataCard>
        </div>
    </section>
</template>
