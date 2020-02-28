<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="badge badge-light badge-pill" v-text="favoritesCount"></span>
        <span>Favors</span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn btn-sm', this.isFavorited ? 'btn-primary' : 'btn-secondary'];
            }
        },

        methods: {
            toggle() {
                if (this.isFavorited) {
                    axios.delete('/replies/' + this.reply.id + '/favorites');

                    this.isFavorited = false;
                    this.favoritesCount--;
                } else {
                    axios.post('/replies/' + this.reply.id + '/favorites');

                    this.isFavorited = true;
                    this.favoritesCount++;
                }
            }
        }
    }
</script>
