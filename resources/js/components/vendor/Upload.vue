<template>
    <div>
        <div @click="$event.target.parentNode.querySelector('input').click()">
            <slot></slot>
            <input @change="onChange" class="d-none" type="file" name="files" :multiple="multiple" :accept="accept">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        accept: {
            type: String,
            default: ''
        },
        uploadKey: {
            type: String,
            default: ''
        },
        url: {
            type: String,
            default: 'api/v1/upload'
        },
        multiple: {
            type: String,
            default: false
        }
    },
    data() {
        return {
            indexId: 0,
            uploads: {},
            start: false,
            remained: 0
        }
    },
    methods: {
        onChange(event) {
            var files = event.target.files;
            var addedFiles = {};
            Object.keys(files).forEach(i => {
                var formData = new FormData();
                var elementId = "i" + this.indexId;
                formData.append("file", files[i]);
                formData.append("key", this.uploadKey);
                this.uploads[elementId] = formData;
                addedFiles[elementId] = {
                    id: elementId,
                    file: files[i]
                };
                this.indexId++;
            });
            this.$emit('before', addedFiles);
            if (!this.start) {
                this.upload();
                this.start = true;
            }
        },
        upload() {
            var data = null;
            for( var index in this.uploads ) {
                data = this.uploads[index];
                this.uploads[index] = 0;
                delete this.uploads[index];
                break;
            }
            this.remained = Object.keys(this.uploads).length;
            var url = document.querySelector('meta[name="url"]').content + '/' + this.url;
            axios.post(url, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function(progressEvent) {
                    this.$emit('progress', Math.round((progressEvent.loaded * 100) / progressEvent.total));
                }.bind(this)
            }).then(response => {
                this.$emit('complete', 'success', response.data, this.remained);
                if (this.remained > 0) {
                    this.upload();
                } else {
                    this.start = false;
                }
            }).catch(error => {
                this.$emit('complete', 'error', [], this.remained);
                if (this.remained > 0) {
                    this.upload();
                } else {
                    this.start = false;
                }
            });
        }
    }
}
</script>
