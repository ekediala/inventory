<template>
    <div class="alert succeed alert-success" role="alert">
        {{ message }}
    </div>
</template>

<script>
export default {
    name: 'Success',
    props: {
        message: {
            required: true,
            type: String,
        },
    },
};
</script>

<style scoped>
.succeed {
    max-width: 20rem;
}
</style>
