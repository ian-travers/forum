<template>
    <div>
        <input type="file" accept="image/*" @change="onChange">
    </div>
</template>

<script>
    export default {
        methods: {
            onChange(el) {
                if (!el.target.files.length) return;

                let newFile = el.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(newFile);

                reader.onload = ev => {
                    let src = ev.target.result;

                    this.$emit('loaded', {src, newFile});
                };
            }
        }
    }
</script>
