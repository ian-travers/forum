<template>
    <div>
        <p>
            <span class="h3" v-text="user.name"></span>
            <small v-text="since"></small>
        </p>

        <div class="d-flex align-items-center">
            <img :src="avatar" width="50" height="50" alt="avatar" class="mr-2">
            <form v-if="canUpdate" method="post" enctype="multipart/form-data">
                <image-upload name="avatar" @loaded="onLoad"></image-upload>
            </form>
        </div>

        <hr>
    </div>
</template>

<script>
    import moment from "moment";
    import ImageUpload from "./ImageUpload";

    export default {
        props: ['user'],

        components: {ImageUpload},

        data() {
            return {
                avatar: this.user.avatar_path,
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            },

            since() {
                return "since " + moment(this.user.created_at).fromNow();
            },
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;

                // Persist to the server
                this.persist(avatar.newFile);
            },

            persist(newAvatarFile) {
                let data = new FormData();

                data.append('avatar', newAvatarFile);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar is uploaded.'));
            }
        }
    }
</script>
