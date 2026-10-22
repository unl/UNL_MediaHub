document.addEventListener('DOMContentLoaded', function () {

    var addTagsForm = document.querySelector('form#addTags');
    if (addTagsForm) {
        addTagsForm.style.display = 'none';
    }

    document.querySelectorAll('#mediaTagsAdd a[href="#"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // prevent default action

            // Hide the link
            link.style.display = 'none';

            // Show sibling form(s) and focus the new tag input
            Array.from(link.parentNode.children).forEach(function (sibling) {
                if (sibling !== link && sibling.matches('form')) {
                    sibling.style.display = '';
                }
            });

            var newTag = document.getElementById('new_tag');
            if (newTag) {
                newTag.focus();
            }
        });
    });
});