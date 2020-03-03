<template>
    <ul class="pagination mt-3" v-if="shouldPaginate">
        <li class="page-item" v-if="prevUrl">
            <a class="page-link" href="#" aria-label="Previous" rel="prev">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li class="page-item" v-if="nextUrl">
            <a class="page-link" href="#" aria-label="Next" rel="next">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['dataSet'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            }
        },

        computed: {
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        }
    }
</script>
