<script setup>
defineProps({
    form: Object,
    tags: Object,
})

</script>

<template>
    <h2 class="my-4 uppercase font-bold text-sm text-ui-link-text">Tags</h2>
    <div class="flex flex-col max-h-[300px] overflow-y-auto text-ui-link-text">
        <label v-for="tag in tags" class="block tag-checkbox inline-block m-1 text-sm">
            <input @change="$emit('checked')" :id="'tag'+ tag.slug" :name="'tag'+ tag.slug" :value="tag.slug" v-model="form.tags"
                   type="checkbox" checked>
            <span>{{ tag.name }} ({{ tag.katas_count }})</span>
        </label>
    </div>
</template>

<style>

.tag-checkbox input[type=checkbox] {
    z-index: -1;
    opacity: 0;
    display: block;
    width: 0;
    height: 0;
}

.tag-checkbox span {
    cursor: pointer;
}

.tag-checkbox span:hover {
    color: rgb(40, 137, 204)
}

.tag-checkbox input[type=checkbox]:checked + span {
    color: rgb(187, 67, 44);
}
</style>
