<script>
    $(document).ready(function() {
        $('.select2').select2({     
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            minimumResultsForSearch: 10,
            language: {
                  noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
        });
    });
</script>