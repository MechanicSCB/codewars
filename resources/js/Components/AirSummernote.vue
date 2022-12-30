<script>

export default {
    props:[
        'summernoteId'
    ],
    mounted() {
        var summernoteComponent = this;

        $('#' + this.summernoteId).summernote({
            airMode: true,
            popover: {
                air: [
                    ['style', ['style']],
                    ['color', ['color']],
                    ['font', ['bold', 'underline', 'superscript','subscript', 'strikethrough', 'clear', 'fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'undo', 'redo', 'help']]
                ]
            },
            callbacks: {
                onChange: function(contents) {
                    summernoteComponent.$emit('submitted', contents, summernoteComponent.summernoteId);
                }
            }

        });
    }
}
</script>

<template>
    <textarea></textarea>
</template>

<style>
    .note-editing-area .note-editable{
        border: 1px solid #999 !important;
        min-height: 120px !important;
        padding: 5px 10px !important;
    }
</style>
