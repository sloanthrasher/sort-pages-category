// scripts.js
jQuery(document).ready(function($) {
    $('#the-list tbody').sortable({
        handle: '.page-title',
        update: function(event, ui) {
            // Do nothing here, we'll save on button click
        }
    });

    $('#save-changes').on('click', function() {
        // Collect order and categories data
        let order = $('#the-list tbody').sortable('toArray', { attribute: 'data-id' });
        let categories = {};

        $('#the-list tbody tr').each(function() {
            let pageId = $(this).data('id');
            let category = $(this).find('.category-selector').val();
            categories[pageId] = category;
        });

        // Send data to REST API
        $.ajax({
            url: sppRestApi.root + 'spp/v1/save-order-and-category',
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', sppRestApi.nonce);
            },
            data: JSON.stringify({ order: order, categories: categories }),
            contentType: 'application/json',
            success: function(response) {
                console.log(response);
                location.reload(); // Reload the page to reflect the new order
            }
        });
    });
});