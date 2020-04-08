<template>
    <div class="container">
        <p class="display-3 text-center" @click="checkJS">Search Test</p>
        <ais-instant-search :search-client="searchClient" index-name="threads">
            <div>
                <ais-search-box />
                <div class="card mt-2">
                    <div class="card-header">
                        Channels
                    </div>
                    <div class="card-body">
                        <ais-refinement-list attribute="channel.name" />
                    </div>
                </div>
            </div>
            <ais-hits>
                <div slot="item" slot-scope="{ item }">
                    <div class="hit-title">
                        <a :href="item.path">
                            <ais-highlight attribute="title" :hit="item"></ais-highlight>
                        </a>
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
    .ais-Hits-list {
        margin-top: 0;
        margin-bottom: 1em;
    }

    .ais-InstantSearch {
        display: grid;
        grid-template-columns: 1fr 4fr;
        grid-gap: 1em;
    }

    .hit-title {
        margin-bottom: 0.5em;
    }

    .hit-body {
        color: #888;
        font-size: 0.8em;
        margin-bottom: 0.5em;
    }
</style>
