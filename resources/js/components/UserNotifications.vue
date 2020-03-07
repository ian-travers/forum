<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a id="navbarDropdown" class="nav-link dropdown-toggle notification-bell" href="#" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><span class="fas fa-bell"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a v-for="notification in notifications" href="#" class="dropdown-item">111</a>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications: ['one']
            }
        }
    }
</script>

<style scoped>

</style>
