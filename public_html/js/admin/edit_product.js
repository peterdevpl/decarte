var $collectionHolder;

var $addLink = $('<a class="btn btn-default" role="button">Dodaj nowe zdjęcie</a>');
var $newLinkLi = $('<li></li>').append($addLink);

function addImageForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);

    addImageFormDeleteLink($newFormLi);

    $newLinkLi.before($newFormLi);
}

function addImageFormDeleteLink($imageFormLi) {
    var $removeFormA = $('<a class="btn btn-danger" role="button">usuń zdjęcie</a>');
    $imageFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $imageFormLi.remove();
    });
}

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('#images');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addImageFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input[type="file"]').length);

    $addLink.on('click', function() {
        // add a new tag form (see next code block)
        addImageForm($collectionHolder, $newLinkLi);
    });
});
