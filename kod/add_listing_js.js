 document.addEventListener('DOMContentLoaded', function() {
    var categorySelect = document.getElementById('category_id');
    var breedSelect = document.getElementById('breed_option');
    var newBreedInput = document.getElementById('new_breed');

    function filterBreeds() {
    var selectedCategory = categorySelect.value;
    var firstVisibleOption = null;

    for (var i = 0; i < breedSelect.options.length; i++) {
    var option = breedSelect.options[i];
    if (option.getAttribute('data-category-id') === selectedCategory || option.value === 'new') {
    option.style.display = '';
    if (!firstVisibleOption) {
    firstVisibleOption = option;
}
} else {
    option.style.display = 'none';
}
}

    if (firstVisibleOption) {
    breedSelect.value = firstVisibleOption.value;
}
}

    function toggleNewBreedInput() {
    if (breedSelect.value === 'new') {
    newBreedInput.style.display = '';
    newBreedInput.required = true;
} else {
    newBreedInput.style.display = 'none';
    newBreedInput.required = false;
}
}

    categorySelect.addEventListener('change', filterBreeds);
    breedSelect.addEventListener('change', toggleNewBreedInput);

    // Initial filtering on page load
    filterBreeds();
    toggleNewBreedInput();
});