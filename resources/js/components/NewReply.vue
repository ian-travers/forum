<template>
    <div>
        <!--        @if(auth()->check())-->
        <!--        <form action="{{ $thread->path() . '/replies' }}" method="post">-->
        <div class="form-group mt-3">
            <textarea name="body" id="body" class="form-control" rows="5"
                      placeholder="Have something to say?" v-model="body" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        <!--        </form>-->

        <!--        @else-->
        <!--        <p class="text-center mt-3">Please <a href="{{ route('login') }}">sign in</a> to participate to this-->
        <!--            discussion.</p>-->
        <!--        @endif-->
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
