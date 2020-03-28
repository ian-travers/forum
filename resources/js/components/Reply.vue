<template>
    <div :id="'reply-' + id" class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
                    said <span v-text="ago"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
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

        <div class="card-footer" v-if="canUpdate">
            <button class="btn btn-sm btn-primary" @click="editing = true">Edit</button>
            <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
        </div>

    </div>
</template>

<script>
    import Favorite from "./Favorite";
    import moment from "moment";

    export default {
        props: ['data'],

        components: {Favorite},

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            };
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            },

            ago() {
                return moment(this.data.created_at).fromNow();
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {body: this.body})
                    .then(response => {
                        this.data.body = this.body;
                        flash('Updated!');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;
            },

            cancel() {
                this.editing = false;
                this.body = this.data.body;
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>
