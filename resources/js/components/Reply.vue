<template>
    <div :id="'reply-' + id" class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <a :href="'/profile/' + data.owner.name" v-text="data.owner.name"></a>
                    said {{ data.created_at }}...
                </div>
                <div>

<!--                    @if(auth()->check())-->
<!--                    <favorite :reply="{{ $reply }}"></favorite>-->

<!--                    @endif-->
                </div>
            </div>

        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" name=""></textarea>
                </div>
                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-secondary" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

<!--        @can('update', $reply)-->
        <div class="card-footer">
            <button class="btn btn-sm btn-primary" @click="editing = true">Edit</button>
            <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
        </div>

<!--        @endcan-->
    </div>
</template>

<script>
    import Favorite from "./Favorite";

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

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                $(this.$el).fadeOut(600, () => {
                    flash('Your reply has been deleted.');
                });
            }
        }
    }
</script>
