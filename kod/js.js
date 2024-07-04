document.addEventListener('DOMContentLoaded', function() {
    var categorySelect = document.getElementById('animal_type');
    var breedSelect = document.getElementById('breed');

    categorySelect.addEventListener('change', function() {
        var selectedCategoryId = categorySelect.value;

        // Prikaži samo opcije koje odgovaraju izabranoj kategoriji
        for (var i = 0; i < breedSelect.options.length; i++) {
            var option = breedSelect.options[i];
            var categoryId = option.getAttribute('data-category-id');

            if (selectedCategoryId === '' || categoryId === selectedCategoryId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        }

        // Resetuj izbor rase
        breedSelect.value = '';
    });

    // Inicijalno filtriranje na osnovu izabrane kategorije (ako postoji)
    var initialCategoryId = categorySelect.value;
    if (initialCategoryId !== '') {
        categorySelect.dispatchEvent(new Event('change'));
    }
});