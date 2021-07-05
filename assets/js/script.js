

// JQUERY CODE ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function(){
    const apiUrl = 'https://geo.api.gouv.fr/communes?codePostal=';
    const format = '&format=json';

    let cp = $('#cp'); let ville = $('#ville'); let errorMessage = $('#error-message');

    $(cp).on('blur', function(){
        let code = $(this).val();
       //console.log(code);
       let url = apiUrl+code+format;
       //console.log(url);

       fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
        //console.log(results);
        $(ville).find('option').remove();
        if(results.length){
            $(errorMessage).text('').hide();
            $.each(results, function(key, value){
            console.log(value.nom);
            $(ville).append('<option value="'+value.nom+'">'+value.nom+'</option>');

            });
        }
        else{
            if($(cp).val()){
                console.log('Ce code postal n\'existe pas.');
                $(errorMessage).text('Aucune commune avec ce code postal.').show();
            }else{
                $(errorMessage).text('').hide();
            }
        }
       }).catch(err => {
           console.log(err);
           $(ville).find('option').remove();
       });
    });


    $('#search').keyup(function(){
        $('#result-search').html('');
        let utilisateur = $(this).val();

        if(utilisateur != ""){
            $.ajax({
                type: 'GET',
                url: 'recherche_annonce.php',
                data: 'user=' + encodeURIComponent(utilisateur),
                success: function(data){
                    if(data != ""){
                        $('#result-search').append(data);
                    }else{
                        document.getElementById('result-search').innerHTML = '<div class="col-12 alert alert-danger">Aucun produit ne correspond à votre recherche !</div>';
                    }
                }
            });
        }   
    });
});
//END OF JQUERY CODE //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    let exampleModal = document.getElementById('exampleModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    let button = event.relatedTarget
    // Extract info from data-bs-* attributes
    let recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    let modalTitle = exampleModal.querySelector('.modal-title')
    let modalBodyInput = exampleModal.querySelector('.modal-body input')

    modalTitle.textContent = 'New message to ' + recipient
    modalBodyInput.value = recipient
    })

    