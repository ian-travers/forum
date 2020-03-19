<template>
    <div>
        <p>
            <span class="h3" v-text="user.name"></span>
            <small v-text="since"></small>
        </p>

        <form v-if="canUpdate" method="post" enctype="multipart/form-data">

            <div class="d-flex justify-content-between align-items-baseline">
                <div class="form-group">
                    <input type="file" name="avatar" accept="image/*" @change="onChange">
                </div>
            </div>

        </form>

        <img :src="avatar" width="50" height="50" alt="avatar">
        <hr>
    </div>
</template>

<script>
    import moment from "moment";

    export default {
        props: ['user'],

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
            onChange(el) {
                if (!el.target.files.length) return;

                let newAvatarFile = el.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(newAvatarFile);

                reader.onload = ev => {
                    // console.log(ev);
                    this.avatar = ev.target.result;
                };

                this.persist(newAvatarFile);
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

<style scoped>

</style>
