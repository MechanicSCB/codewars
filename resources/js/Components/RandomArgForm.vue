<script setup>
let props = defineProps({
    form: Object,
    arg: Object,
    argKey: Number,
    testKey: Number,
});

console.log(props.form.tests[0].args[1]['element']);
</script>

<template>
    <div v-for="param in ['type','length','value','allowed','precision','element']"
         class="my-2">
        <label class="my-2 w-full">
            <span>{{ param }}: </span>
            <input class="w-[700px] p-1 rounded" v-model="form.tests[testKey].args[argKey][param]"/>
        </label>
    </div>
</template>
