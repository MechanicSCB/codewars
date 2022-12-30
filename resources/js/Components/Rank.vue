<script setup>

defineProps({
    rank: Number,
});
</script>

<template>
    <div class="block rank px-1" :class="'rank-' + rank">
        <svg class="absolute w-[50px] fill-[rgb(244,244,245)] dark:fill-black" stroke="currentColor" stroke-width="10" viewBox="0 0 129.4 69">
            <polygon class="" points="3.6,34.5 12.8,18.6 22,2.8 107.4,2.8 116.5,18.6 125.7,34.5 116.5,50.4 107.4,66.2 22,66.2 12.8,50.4"/>
        </svg>
        <p class="relative h-full w-[50px] text-center leading-7 text-xs font-bold">{{ rank }} kyu</p>
    </div>
</template>

<style>
.rank-checkbox input[type=checkbox]:checked+div svg,
.rank-checkbox:hover svg{
    fill: currentColor;
}
.rank-checkbox input[type=checkbox]:checked+div p,
.rank-checkbox:hover p{
    color: #fff;
}

.rank-1, .rank-2 {
    color: #866cc7;;
}

.rank-3, .rank-4 {
    color: #3c7ebb;
}

.rank-5, .rank-6 {
    color: #ecb613;
}

.rank-7, .rank-8 {
    color: #999;;
}

.dark .rank-7,
.dark .rank-8 {
    color: #e6e6e6;;
}

.dark .rank-checkbox input[type=checkbox]:checked+div.rank-7 p,
.dark .rank-checkbox:hover .rank-7 p,
.dark .rank-checkbox input[type=checkbox]:checked+div.rank-8 p,
.dark .rank-checkbox:hover .rank-8 p{
    color: #555;
}

</style>
