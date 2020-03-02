<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-3">
            <textarea name="body" id="body" class="form-control" rows="5"
                      placeholder="Have something to say?" v-model="body" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        </div>

        <p class="text-center mt-3" v-else>Please <a href="/login">sign in</a> to participate to this
            discussion.</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                body: '',
                endpoint: '/threads/et/53/replies'
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        methods: {
            addReply() {
                axios
                    .post(this.endpoint, {body: this.body})
                    .then(response => {
                        this.body = '';

                        flash('You reply has been posted.');

                        this.$emit('created', response.data);
                    });
            }
        }
    }
</script>
