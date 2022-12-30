<script setup>
import Rank from "../../Components/Rank.vue";
import KataInfo from "../../Components/KataInfo.vue";
import KataTags from "../../Components/KataTags.vue";
import Highlight from "../../Components/Highlight.vue";

defineProps({
    kata: Object,
});
</script>

<template>
    <Head :title="kata.name"/>

    <div class="bg-ui-section rounded-lg" data-title="{{ kata.name }}" id="{{ kata.id }}">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center px-2 md:px-4 py-2 mb-0">
            <div class="my-2 px-1 w-full md:w-5/12">
                <div class="flex items-center space-x-2">
                    <Rank :rank="kata.rank"/>
                    <h4 class="font-bold">{{ kata.name }}</h4>
                </div>
                <KataInfo :kata="kata"/>
            </div>
            <div class="w-full md:w-7/12">
                <div class="flex flex-col sm:flex-row sm:justify-around sm:items-center px-8 md:px-0">
                    <div class="w-full sm:w-1/3">
                         <x-kata.lang-select :kata="kata" />
                    </div>
                    <div class="w-full sm:w-2/3 py-2 md:py-0">
                        <div class="text-center md:text-right whitespace-nowrap">
                            <Link class="btn btn-blue"
                               :href="'/katas/' + kata.slug + '/train'"
                               id="play_btn"
                               title="Train on this challenge"
                            ><i class="icon-moon-play "> </i>Train</Link
                            ><Link class="btn ml-1 sm:ml-2"
                                href="/trainer/javascript"
                                id="play_next_btn"
                                title="Train on another challenge"
                        ><i class="icon-moon-play"> </i>Next Kata</Link>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="w-full mt-2">
        <div class="flex flex-row justify-between">
            <x-kata.show-menu :kata="kata"/>

            <x-kata.social :kata="kata" />
        </div>
        <div class="w-full p-5 mb-5 bg-ui-section rounded-lg">
            <Highlight class="description" :content="kata.description"></Highlight>

            <KataTags :tags="kata.tags" class="!mt-4"/>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-bold">Solutions</h2>
        <div v-for="solution in kata.solutions" :key="solution.id"
             class="w-full p-5 mb-5 bg-ui-section rounded-lg"
        >
            <Highlight :class="'language-' + solution.lang.slug"
                       :content="'<pre>' + solution.body + '</pre>'"/>
        </div>
    </div>
</template>
