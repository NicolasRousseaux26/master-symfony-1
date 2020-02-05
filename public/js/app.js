var tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: '/tag.json'
});
tags.initialize();

$('[data-role="tagsinput"]').tagsinput({
    typeaheadjs: {
        name: 'tags',
        displayKey: 'name',
        valueKey: 'name',
        source: tags.ttAdapter()
    }
});

$('[type="file"]').on('change', function () {
    var label = $(this).val().split('\\').pop();
    $(this).next().text(label);
    var field = $(this);

    // Récupérer l'image complète
    var reader = new FileReader();
    reader.addEventListener('load', function (file) {
        console.log(file);
        $('.custom-file img').remove(); // On clean les images précèdentes
        var img = $('<img class="img-fluid mt-5" width="250" />');
        img.attr('src', file.target.result);
        var customFile = field.parent();
        customFile.prepend(img);
    });
    reader.readAsDataURL(this.files[0]);
});
