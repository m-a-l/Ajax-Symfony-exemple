$(document).ready(function() {
    searchAction();
    newAction();
    editAction();
    formEditAction();
});
//searchAction
function searchAction() {
    $("#product_search").on('submit', function (e) {
        e.preventDefault();
        $(".loading").show();
        var $this = $(this);
        var keyword = $("#appbundle_research_product_keyword").val();
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: 'keyword='+keyword,
            success: function(data) {
                console.log(data);
                $('#product_list').html(data);
                $(".loading").hide();
            }
        });
    });
}
//newAction
function newAction() {
    $("#product_new_form").on('submit', function (e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function(data) {
                $('#product_list').html(data);
                resetSearchBar();
                formEditAction();
                $this[0].reset();
            }
        });
    });
}
//editAction
function editAction() {
    $(".product_edit_form").on('submit', function (e) {
        console.log('hey');
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            data: $this.serialize(),
            success: function(data) {
                $('#product_list').html(data);
                formEditAction();
                resetSearchBar();
            }
        });

    });
}
//formEditAction
function formEditAction() {
    $(".edit_form_create").on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            url: $this.data('path'),
            type: 'POST',
            data: $this.serialize(),
            success: function(data) {
                $('#edit_form').html(data);
                editAction();
            }
        });
    });
}
//reset searchbar
function resetSearchBar() {
    $("#appbundle_research_product_keyword").val('');
}