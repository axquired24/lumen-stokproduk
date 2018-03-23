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
        productCat: {
            data: []
        },
        productList: {
            data: []
        },
        currentPage: {
            stock: 1
        },
        currentSearch: {
            stock: ''
        },
        isLoading: {
            stock: false
        }
    },
    methods: {
        loadProductCat: function () {
            axios.post(config.api + '/productcat')
            .then(function(response) {
                app.productCat = response.data;
            })
            .catch(function(error) {
                console.log('ERROR: ' + config.api + '/productcat')
                console.log(error);
            })
        },
        loadProduct: function (page=1, q='') {
            this.isLoading.stock = true;
            let hitUrl = config.api + '/product';
            if(q != '') {
                if (q.length < 3) {
                    alert('Keyword pencarian harus lebih dari 3 karakter')
                    this.isLoading.stock = false;
                    return
                }
                hitUrl = config.api + '/product/search';
            }
            axios.post(hitUrl, {
                page: page,
                q: q
            })
                .then(function (response) {
                    app.productList = response.data.data;
                    app.currentPage.stock = page;
                    app.isLoading.stock = false;                    
                })
                .catch(function (error) {
                    console.log('ERROR: ' + hitUrl)
                    console.log(error);
                    app.isLoading.stock = false;                    
                })
        },
        updateStok: function(product) {
            const productDetail = product.name + "(tipe "+ product.product_cat.name +")";
            const updateProductUrl = config.api + "/product/edit";
            let newStock = prompt("Update stok: " + productDetail, product.stock);
            newStock = parseInt(newStock);
            if (! newStock) {
                // false if nothing input
                return
            }

            this.isLoading.stock = true;            
            axios.post(updateProductUrl, {
                id: product.id,
                stock: newStock
            })
                .then(function (response) {
                    app.loadProduct()
                })
                .catch(function (error) {
                    console.log('ERROR: ' + updateProductUrl)
                    console.log(error);
                    app.isLoading.stock = false;
                })
            
        },
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
        },
        searchStock: function() {
            this.isLoading.stock = true;            
            delay(function () {
                app.loadProduct(1, app.currentSearch.stock);
            }, 1000);
        }
    },
    mounted: function() {
        this.loadProductCat();
        this.loadProduct();
    }
})

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();