<template>
    <div class="container">
        <p class="display-3 text-center" @click="checkJS">Search Test</p>

        <ais-instant-search :search-client="searchClient" index-name="threads">
            <ais-search-box class="mb-3"/>
            <ais-hits>
                <div slot="item" slot-scope="{ item }">
                    <div class="hit-title">
                        <ais-highlight attribute="title" :hit="item"></ais-highlight>
                    </div>
                    <div class="hit-body">
                        <ais-highlight attribute="body" :hit="item"></ais-highlight>
                    </div>
                </div>


            </ais-hits>
        </ais-instant-search>
    </div>
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

    export default {
        props: ['algolia_app_id', 'algolia_key'],

        data() {
            return {
                searchClient: algoliasearch(
                    this.algolia_app_id,
                    this.algolia_key
                ),
            };
        },

        methods: {
            checkJS() {
                flash(this.algolia_app_id + ' | ' + this.algolia_key);
            }
        }
    };
</script>

<style scoped>
    .hit-title {
        margin-bottom: 0.5em;
    }
    .hit-body {
        color: #888;
        font-size: 0.8em;
        margin-bottom: 0.5em;
    }
</style>
