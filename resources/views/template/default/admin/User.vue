<template>
    <div>
        <div class="card mt-3">
            <div class="card-header">
                <span>ویرایش اطلاعات {{ user.fullname }}</span>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user'
        ],
        data() {
            return {

            }
        }
    }
</script>
