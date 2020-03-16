<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-3">
                <VueTribute :options="options">
                    <textarea name="body" id="body" class="form-control" rows="5"
                              placeholder="Have something to say?" v-model="body" required></textarea>
                </VueTribute>
                <div class="menu-container" ref="menuContainer"></div>
            </div>

            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        </div>

        <p class="text-center mt-3" v-else>Please <a href="/login">sign in</a> to participate to this
            discussion.</p>
    </div>
</template>

<script>
    import VueTribute from "vue-tribute";

    export default {
        components: {
            VueTribute
        },

        data() {
            return {
                body: '',
                options: {
                    // trigger: "@",
                    values: [
                        {key: "Collin Henderson", value: "syropian"},
                        {key: "Sarah Drasner", value: "sarah_edo"},
                        {key: "Evan You", value: "youyuxi"},
                        {key: "Adam Wathan", value: "adamwathan"}
                    ],
                    positionMenu: true,
                }
            }
        },

        mounted() {
            this.options.menuContainer = this.$refs.menuContainer;
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
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
    @import '../../sass/tribute';
</style>
