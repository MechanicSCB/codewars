<script setup>
import Rank from './Rank.vue'

defineProps({
    form:Object,
})
</script>
<template>
    <h2 class="font-bold">Difficulty</h2>
    <label v-for="rank in [8,7,6,5,4,3,2,1]" class="rank-checkbox inline-block m-1 cursor-pointer">
        <input @change="$emit('checked')" :id="'r'+ rank" :name="'r'+ rank" :value="rank" v-model="form.ranks" type="checkbox"
        class="w-0 h-0 opacity-0 block -z-1">
        <Rank :rank="rank"/>
    </label>
</template>
