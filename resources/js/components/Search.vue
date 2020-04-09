<template>
    <ais-instant-search :query="query" :search-client="searchClient" index-name="threads">
        <div>
            <ais-configure :query="query" :hitsPerPage="8"/>
            <ais-search-box/>
            <div class="card mt-2">
                <div class="card-header">
                    Channels
                </div>
                <div class="card-body">
                    <ais-clear-refinements class="mb-3">
                        <span slot="resetLabel">Clear All</span>
                    </ais-clear-refinements>
                    <ais-refinement-list attribute="channel.name"/>
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
</template>

<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

    export default {
        props: ['algolia_app_id', 'algolia_key', 'query'],

        data() {
            return {
                searchClient: algoliasearch(
                    this.algolia_app_id,
                    this.algolia_key,
                ),
            };
        },

        created() {
            console.log('tut');
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
