<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a id="navbarDropdown" class="nav-link dropdown-toggle notification-bell" href="#" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><span class="fas fa-bell"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <div v-for="(notification, index) in notifications" class="dropdown-item notification-item">
                <strong v-text="notification.data.author"></strong>
                <span v-text="notification.data.action"></span><br>
                <a :href="notification.data.link"
                    v-text="notification.data.thread"
                    @click="markAsRead(notification, index)"
                ></a><br>
                <span v-text="notification.data.at" class="float-right small"></span><br>
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data)
        },

        methods: {
            markAsRead(notification, index) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
                this.notifications.splice(index, 1);
            }
        }
    }
</script>

<style scoped>

</style>
