$(document).ready(function() {
    // Dodavanje u favorites
    $('.add-to-favorites').click(function(e) {
        e.preventDefault(); // Prevent default link behavior

        var listingId = $(this).data('id');

        $.ajax({
            url: 'favorite_pets.php',
            type: 'GET',
            data: {
                action: 'add',
                id: listingId
            },
            success: function(response) {
                alert('Added to favorites');
                // Optionally, update UI or handle success logic
            },
            error: function(xhr, status, error) {
                alert('Failed to add to favorites');
                console.error('Error:', error);
            }
        });
    });

    // Uklanjanje iz favorites
    $('.remove-from-favorites').click(function(e) {
        e.preventDefault(); // Prevent default link behavior

        var listingId = $(this).data('id');

        $.ajax({
            url: 'favorite_pets.php',
            type: 'GET',
            data: {
                action: 'remove',
                id: listingId
            },
            success: function(response) {
                alert('Removed from favorites');
                // Optionally, update UI or handle success logic
            },
            error: function(xhr, status, error) {
                alert('Failed to remove from favorites');
                console.error('Error:', error);
            }
        });
    });
});