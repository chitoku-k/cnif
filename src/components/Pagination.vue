<template>
    <div class="threshold"></div>
</template>

<script>
    import Vuex from 'vuex';
    import { ARTICLE_FETCH } from '@/store/actions';

    export default {
        name: 'Pagination',
        computed: {
            ...Vuex.mapState({
                hasNext: state => state.articles.hasNext,
                hasPrevious: state => state.articles.hasPrevious,
            }),
        },
        mounted() {
            this.observer = new IntersectionObserver(entries => {
                if (entries[0].intersectionRatio <= 0) {
                    return;
                }
                this.next();
            });
            this.observer.observe(this.$el);
        },
        methods: {
            next() {
                this.$store.dispatch(ARTICLE_FETCH, 'next');
            },
        },
    };
</script>

<style scoped>
    .threshold {
        height: 1px;
    }
</style>
