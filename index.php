<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .category-list {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h3>Categories</h3>
            <ul class="list-group category-list" id="category-list">
                <!-- Categories will be loaded here via AJAX -->
            </ul>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between">
                <h3>Products</h3>
                <select id="sort-order" class="form-control w-auto">
                    <option value="date">Sort by Date</option>
                    <option value="price">Sort by Price</option>
                    <option value="name">Sort by Name</option>
                </select>
            </div>
            <div id="product-list" class="row">
                <!-- Products will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Product details will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadCategories();
        loadProducts();

        // Load categories
        function loadCategories() {
            $.getJSON("getCategories.php", function(data) {
                var categoryList = $("#category-list");
                categoryList.empty();
                $.each(data, function(key, category) {
                    categoryList.append('<li class="list-group-item category-item" data-id="' + category.id + '">' + category.name + ' (' + category.product_count + ')</li>');
                });
            });
        }

        // Load products
        function loadProducts(category_id = null, order_by = 'date') {
            $.getJSON("getProducts.php", {category_id: category_id, order_by: order_by}, function(data) {
                var productList = $("#product-list");
                productList.empty();
                $.each(data, function(key, product) {
                    productList.append('<div class="col-md-4"><div class="card mb-4"><div class="card-body"><h5 class="card-title">' + product.name + '</h5><p class="card-text">$' + product.price + '</p><button class="btn btn-primary buy-button" data-id="' + product.id + '">Buy</button></div></div></div>');
                });
            });
        }

        // Handle category click
        $(document).on("click", ".category-item", function() {
            var category_id = $(this).data("id");
            var order_by = $("#sort-order").val();
            loadProducts(category_id, order_by);
            updateURL(category_id, order_by);
        });

        // Handle sort order change
        $("#sort-order").change(function() {
            var category_id = $(".category-item.active").data("id");
            var order_by = $(this).val();
            loadProducts(category_id, order_by);
            updateURL(category_id, order_by);
        });

        // Handle buy button click
        $(document).on("click", ".buy-button", function() {
            var product_id = $(this).data("id");
            $.getJSON("getProduct.php", {id: product_id}, function(data) {
                $("#productModalLabel").text(data.name);
                $(".modal-body").html('<p>Price: $' + data.price + '</p><p>Date: ' + data.date + '</p>');
                $("#productModal").modal("show");
            });
        });

        // Update URL with parameters
        function updateURL(category_id, order_by) {
            var url = new URL(window.location.href);
            url.searchParams.set('category', category_id);
            url.searchParams.set('order', order_by);
            window.history.pushState({}, '', url);
        }

        // Load parameters from URL
        function loadParamsFromURL() {
            var url = new URL(window.location.href);
            var category_id = url.searchParams.get('category');
            var order_by = url.searchParams.get('order') || 'date';
            if (category_id) {
                loadProducts(category_id, order_by);
                $("#sort-order").val(order_by);
            }
        }

        loadParamsFromURL();
    });
</script>
</body>
</html>
