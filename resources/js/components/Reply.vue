<template>
    <div :id="'reply-' + id" class="card mt-3" :class="isBest ? 'bg-best' : ''">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a>
                    said <span v-text="ago"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-secondary" @click="cancel" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div class="d-flex justify-content-between">
                <div>
                    <div v-if="authorize('owns', reply)">
                        <button class="btn btn-sm btn-primary" @click="editing = true">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
                    </div>
                </div>
                <div>
                    <button
                        class="btn btn-sm btn-success"
                        @click="markBestReply"
                        v-if="authorize('owns', reply.thread) && !isBest"
                    >Best Reply</button>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
    import Favorite from "./Favorite";
    import moment from "moment";

    export default {
        props: ['reply'],

        components: {Favorite},

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow();
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id)
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {body: this.body})
                    .then(response => {
                        this.reply.body = this.body;
                        flash('Updated!');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;
            },

            cancel() {
                this.editing = false;
                this.body = this.reply.body;
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>

<style scoped>
    .bg-best {
        background-color: #e2f7eb !important;
    }
</style>
