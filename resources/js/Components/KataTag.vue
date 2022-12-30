<script setup>
import {computed, onMounted} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

let props = defineProps({
    tag: Object,
    isButton: Boolean,
});

let getUrlParams = () => {
    let urlParams = {};

    (window.onpopstate = function () {
        let match,
            pl = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) {
                return decodeURIComponent(s.replace(pl, " "));
            },
            query = window.location.search.substring(1);

        while (match = search.exec(query)) {
            if (decode(match[1]) in urlParams) {
                if (!Array.isArray(urlParams[decode(match[1])])) {
                    urlParams[decode(match[1])] = [urlParams[decode(match[1])]];
                }
                urlParams[decode(match[1])].push(decode(match[2]));
            } else {
                urlParams[decode(match[1])] = decode(match[2]);
            }
        }
    })();

    return urlParams;
}

let isActiveTag = (tag) => {
    return window.location.search.includes(tag.slug);
};

let urlParams = getUrlParams();

let submitTag = () => {
    if(props.isButton){
        let tag = props.tag.slug;
        let href = window.location.href;
        // TODO refactor (merge with KataFilter setQueryArgsToFilterForm function) and fix tag with space bug (Data%2520Types, Data Types)
        // let urlParams = getUrlParams();

        if (Object.keys(urlParams).length === 0) {
            href += '?tags[]=' + tag;
        } else if ((urlParams['tags[]'] ?? []).indexOf(tag) !== -1) {
            href = href.replace('tags[]=' + tag, '');
            href = href.replace('&&', '&');
            href = href.replace('?&', '?');
        } else {
            href += '&tags[]=' + tag;
        }

        Inertia.get(href);
    }
}
</script>

<template>
    <div @click.prevent="submitTag"
         class="inline-block rounded uppercase bg-black/10 dark:bg-black/20 py-1.5 px-2 mr-2 mb-2 text-xxs"
         :class="(isActiveTag(tag) ? 'text-red-700' : '') + (isButton ? ' hover:text-ui-link-hover cursor-pointer' : '')"
    >{{ tag.name }}
    </div>
</template>
