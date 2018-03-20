import Vue from 'vue';
import Vuex from 'vuex';
import { INITIALIZE, ARTICLE_FETCH } from './actions';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        articles: {
            records: [],
            hasPrevious: null,
            previousCursor: null,
            hasNext: null,
            nextCursor: null,
        },
    },
    mutations: {
        [ARTICLE_FETCH](state, { articles, direction }) {
            state.articles = {
                ...articles,
                records: [
                    ...state.articles.records,
                    ...articles.records,
                ],
            };
        },
    },
    actions: {
        async [ARTICLE_FETCH]({ commit, state }, direction) {
            const cursor = JSON.stringify(state.articles[`${direction}Cursor`]);
            const response = await fetch(`/api/articles/${direction}/?${direction}_cursor=${cursor}`);
            const { articles } = await response.json();
            commit(ARTICLE_FETCH, {
                articles,
                direction,
            });
        },
    },
});
