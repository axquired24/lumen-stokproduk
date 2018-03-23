const CATEGORY_STR = 'cat',
      LIST_STR = 'list',
      STOCK_STR = 'stock',
      ADD_STR = 'add',
      EDIT_STR = 'edit',
      HOME_STR = 'home';

var app = new Vue({
    el: '#app',
    data: {
        pageState: HOME_STR,
        message: 'Hello Vue!'
    },
    methods: {
        catShow: function () {
            this.pageState = CATEGORY_STR;
        },
        listShow: function () {
            this.pageState = LIST_STR;
        },
        stockShow: function () {
            this.pageState = STOCK_STR;
        },
        addShow: function () {
            this.pageState = ADD_STR;
        },
        editShow: function () {
            this.pageState = EDIT_STR;
        },
        homeShow: function () {
            this.pageState = HOME_STR;
        },
        catView: function () {
            return this.pageState == CATEGORY_STR;
        },
        listView: function () {
            return this.pageState == LIST_STR;
        },
        stockView: function () {
            return this.pageState == STOCK_STR;
        },
        addView: function () {
            return this.pageState == ADD_STR;
        },
        editView: function () {
            return this.pageState == EDIT_STR;
        },
        homeView: function () {
            return this.pageState == HOME_STR;
        }
    }
})