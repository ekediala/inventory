<template>
    <div class="alert error alert-danger" role="alert">
        <ul>
            <li v-for="error in errors">
                {{ error }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'Error',
    props: {
        errors: {
            required: true,
        },
    },
};
</script>

<style scoped>
    .error {
        max-width: 20rem;
    }
</style>
