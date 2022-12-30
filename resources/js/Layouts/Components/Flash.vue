<script>
export default {
    updated() {
        setTimeout(() => {
            document.querySelector('div#flash-container').classList.add('fade')
        }, 2000);

        document.querySelector('div#flash-container').classList.remove('fade')
    }
}
</script>

<template>
    <div id="flash-container" class="fixed right-6 bottom-6 z-[100]">
        <div v-if="$page.props.flash.message" class="bg-orange-500 text-white w-fit rounded px-4 py-1">
            {{ $page.props.flash.message }}
        </div>
        <div v-if="$page.props.flash.success" class="bg-green-500 text-white w-fit rounded px-4 py-1">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash.error" class="bg-red-500 text-white w-fit rounded px-4 py-1">
            {{ $page.props.flash.error }}
        </div>
    </div>
</template>

<style>
.fade {
    opacity: 0;
    transition-property: opacity;
    transition-duration: .25s;
}
</style>
