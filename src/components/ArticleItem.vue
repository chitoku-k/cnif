<template>
    <article>
        <h2>
            {{ article.title }}
            <aside class="datetime">{{ article.created }}</aside>
        </h2>
        <div class="author">作成者: {{ article.author }}</div>
        <div class="container">
            <img :src="'https://picsum.photos/160/160/?random&' + article.image" alt="" width="80" height="80">
            <p>{{ article.body }}</p>
        </div>
    </article>
</template>

<script>
    export default {
        name: 'ArticleItem',
        props: {
            article: Object,
        },
    };
</script>

<style scoped>
    article {
        background: #fff;
        box-shadow: 0px 2px 4px 0px #d2d2d2;
        border-radius: 3px;
        padding: 20px 30px;
        margin-bottom: 20px;
    }

    .author, .datetime {
        color: #666;
        font-size: 14px;
    }

    .datetime {
        font-weight: normal;
        flex-shrink: 0;
    }

    h2 {
        font-size: 24px;
        margin-top: 0;
        margin-bottom: 10px;
        border-bottom: 1px solid #ccc;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .container {
        display: flex;
        margin-top: 15px;
    }

    img {
        border: 1px solid #ccc;
        padding: 4px;
        flex-shrink: 0;
    }

    p {
        margin-top: 0;
        margin-left: 15px;
    }
</style>
