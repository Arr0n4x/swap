let modifier_profil = document.querySelector('#modifprofil');
let hide_profil = document.querySelector('#annuler');
let form_profil = document.querySelector('#formprofil');


function showProfil(){
    form_profil.classList.add('show');
}

modifier_profil.addEventListener('click', showProfil);

function hideProfil(){
    form_profil.classList.add('hidden');
}
hide_profil.addEventListener('click', hideProfil);

