<script setup>
import { computed } from 'vue';

let props = defineProps({
    kata: Object,
});

const rating = computed(() => props.kata.total_attempts ? Math.round(props.kata.total_completed/props.kata.total_attempts*100) : 0);

</script>

<template>
    <div class="mt-2 mb-3 text-sm" :data-id="kata.id">
        <Link class="mr-2 hover:text-ui-link-hover" v-tippy="'Total times this kata has been bookmarked. Click to bookmark'"
              href="#" preserve-scroll>
            <i class="icon-moon-star text-sm opacity-75 mr-2"></i>
            <span>{{ kata.total_stars }}</span>
        </Link>
        <a class="mr-2 hover:text-ui-link-hover" v-tippy="'Total collections this kata is a part of. Click to view and add collections.'">
            <i class="icon-moon-collection text-sm opacity-75 mr-2"></i>
            <span>{{ kata.vote_score }}</span>
        </a>
        <span class="ml-1 text-ui-text-lc inline-block" v-tippy="'Satisfaction Rating: '+ rating +'% of users gave a positive rating out of ' + kata.total_attempts + ' votes. Ranked kata must be completed before they can be voted on.'">
            <i class="icon-moon-guage text-xs opacity-75 top-0 mr-1"></i>
            <span class="mr-2">{{ rating }}%
                <span class="opacity-75">of</span>
                {{ kata.total_attempts }}
            </span>
        </span>
        <span class="mr-2 text-ui-text-lc inline-block">
            <i class="icon-moon-bullseye text-sm opacity-75"></i>
            {{ kata.total_completed }}
        </span>
        <a v-if="kata.creator" class="mr-4 inline-block hover:text-ui-link-hover" :href="'/users/'+ kata.creator.id" v-tippy="'This kata\'s Sensei'">
            <i class="icon-moon-user text-sm opacity-75"></i>
            {{ kata.creator.name }}
        </a>
        <a v-if="kata.unresolved_issues" class="mr-4 inline-block hover:text-ui-link-hover" :href="'/kata/'+ kata.id + '/discuss#label-issue'">
            <i class="icon-moon-warning text-sm opacity-75"></i>
            {{ kata.unresolved_issues }} Issue Reported
        </a>
    </div>
</template>
