<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import RankFilter from "./RankFilter.vue";
import TagFilter from "./TagFilter.vue";
import {onMounted, computed} from 'vue';

defineProps({
    sort: Object,
    langs: Object,
    tags: Object,
})

let form = useForm({
    search: '',
    sort_by: 'popularity',
    lang: '',
    status: '',
    progression: '',
    ranks: [],
    tags: [],
});

function submit() {
    form.get('/', {
        preserveScroll: true,
        preserveState: true,
    })
}

onMounted(() => {
    // TODO refactor (merge with KataTag getUrlParams function) and fix tag with space bug (Data%2520Types, Data Types)
    setQueryArgsToFilterForm();
});

let setQueryArgsToFilterForm = () => {
    let uri = Inertia.page.url.split('?');

    if (uri.length === 2) {
        let vars = uri[1].split('&');
        let tmp = '';

        vars.forEach(function (v) {
            tmp = v.split('=');

            if (tmp.length === 2) {
                if (tmp[0] === 'search') form.search = tmp[1];
                if (tmp[0] === 'sort_by') form.sort_by = tmp[1];
                if (tmp[0].startsWith('ranks')) form.ranks.push(tmp[1]);
                if (tmp[0].startsWith('tags')) form.tags.push(tmp[1]);
            }
        });
    }
};
</script>

<template>
    <div class="mx-4 my-4 text-sm">
        <form @submit.prevent="submit">
            <!--  Search  -->
            <div class="mt-1 flex rounded-md shadow-sm">
                <div class="relative flex items-stretch flex-grow focus-within:z-10">
                    <input v-model="form.search"
                           name="search"
                           class="input-gray rounded-none rounded-l-md sm:text-sm"
                           id="search-input" placeholder="Search"
                           type="text"
                    >
                </div>
                <button type="submit"
                        class="btn btn-gray2 rounded-none rounded-r-md -ml-px"
                        id="search-button">
                    <span class="sr-only">Search</span>
                    <svg aria-hidden="true" class="w-4 h-4 text-gray-400 dark:text-gray-300" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
            </div>
            <!--  SELECTS  -->
            <div class="mt-4 mb-6">
                <!--  Sort by  -->
                <label class="mt-2 block font-bold font-xs tracking-wider mb-2 text-gray-700 dark:text-gray-200" for="sort_by">Sort By</label>
                <select @change="submit" v-model="form.sort_by"
                        class="mt-1 select-gray"
                        name="sort_by"
                        id="sort_by"
                >
                    <option v-for="(item, slug) in sort" :value="slug">{{ item.name }}</option>
                </select>

                <!--  Language  -->
                <label class="mt-2 block font-bold font-xs tracking-wider mb-2 text-gray-700 dark:text-gray-200" for="lang">Language</label>
                <select @change="submit" v-model="form.lang"
                        class="mt-1 select-gray"
                        name="lang"
                        id="lang"
                >
                    <option value="">All</option>
                    <option v-for="lang in langs" :value="lang.slug">{{ lang.name }}</option>
                </select>

                <!--  Status  -->
                <label class="mt-2 block font-bold font-xs tracking-wider mb-2 text-gray-700 dark:text-gray-200" for="status">Status</label>
                <select @change="submit" v-model="form.status"
                        class="mt-1 select-gray"
                        name="status"
                        id="status"
                >
                    <option value="">Approved &amp; Beta</option>
                    <option value="approved">Approved</option>
                    <option value="beta">Beta</option>
                </select>

                <!--  Progress  -->
                <label class="mt-2 block font-bold font-xs tracking-wider mb-2 text-gray-700 dark:text-gray-200" for="progression">Progress</label>
                <select @change="submit" v-model="form.progression"
                        class="mt-1 select-gray"
                        name="progression"
                        id="progression"
                >
                    <option value="">All</option>
                    <option value="played">Kata I have not trained on</option>
                    <option value="completed">Kata I have not completed</option>
                    <option value="not_completed">Kata I have completed</option>
                </select>
            </div>
            <RankFilter @checked="submit" :form="form"></RankFilter>
            <hr class="my-2 dark:border-gray-500">
            <TagFilter @checked="submit" :form="form" :tags="tags"></TagFilter>
        </form>
    </div>
</template>
