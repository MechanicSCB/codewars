<template>
    <div v-html="content" ref="content"></div>
</template>

<script>
import Highlighter from 'highlight.js';
// import 'highlight.js/styles/foundation.css'; // load Foundation style

export default {
    props: ['content', 'classes'],

    mounted () {
        this.highlight(this.$refs.content);
    },

    methods: {
        highlight(block) {
            if (!block) return;

            block.querySelectorAll('pre').forEach(function (node) {
                Highlighter.highlightElement(node);
            });
        }
    },

    watch: {
        content() {
            this.$nextTick(() => {
                this.highlight(this.$refs['content']);
            });
        }
    },
}
</script>
