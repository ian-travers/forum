<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-3">
                <textarea
                    name="body"
                    id="body"
                    class="form-control"
                    rows="5"
                    placeholder="Have something to say?"
                    v-model="body"
                    required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        </div>

        <p class="text-center mt-3" v-else>Please <a href="/login">sign in</a> to participate to this
            discussion.</p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        data() {
            return {
                body: '',
            }
        },

        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 700,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function (usernames) {
                            callback(usernames);
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                    .then(response => {
                        this.body = '';

                        flash('You reply has been posted.');

                        this.$emit('created', response.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        }
    }
</script>

<style lang="scss">
    @import '../../sass/atwho';
</style>
