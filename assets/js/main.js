jQuery(document).ready(function($) {

    $('.optin-answer').on('click', function() {
        var answer = $(this).data('answer');
        $.post(parameters.ajax_url, {
            action: 'ceo_save_optin',
            value: answer
        }, function(response) {
            if (response.success) {
                $('#directory-optin-popup').hide();
            } else {
                alert('Something went wrong.');
            }
        });
    })

    function loadUsers(search = '', page = 1) {
        $.ajax({
            url: parameters.ajax_url,
            method: "POST",
            data: {
                action: "ceo_load_users_ajax",
                search: search,
                page: page
            },
            success: function(response) {
                if (response.success) {
                    $('#user-grid').html(response.data.html);
                    $('#user-pagination').html(response.data.pagination);
                } else {
                    $('#user-grid').html('<p>No users found.</p>');
                    $('#user-pagination').html('');
                }
            }
        });
    }

    loadUsers();

    $('#user-search').on('input', function() {
        const searchVal = $(this).val();
        loadUsers(searchVal);
    });

    $('#user-pagination').on('click', 'a.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const searchVal = $('#user-search').val();
        loadUsers(searchVal, page);
    });

});