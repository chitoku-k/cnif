<template>
    <div class="article-list">
        <h1>記事一覧</h1>
        <article-item v-for="article in articles.records" :article="article" :key="article.id"></article-item>
        <pagination></pagination>
    </div>
</template>

<script>
    import Vuex from 'vuex';
    import ArticleItem from '@/components/ArticleItem';
    import Pagination from '@/components/Pagination';
    import { INITIALIZE } from '@/store/actions';

    export default {
        name: 'ArticleList',
        components: {
            ArticleItem,
            Pagination,
        },
        computed: {
            ...Vuex.mapState([
                'articles',
            ]),
        },
    };
</script>

<style scoped>
    h1 {
        border-left: 12px solid #a9cf3c;
        border-bottom: 1px solid #a9cf3c;
        padding: 4px 14px;
        font-size: 26px;
    }
</style>
