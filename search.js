const searchToggleBtn = document.getElementById('searchToggleBtn');
const searchBar = document.getElementById('searchBar');
const searchInput = document.getElementById('searchInput');
const closeSearchBtn = document.getElementById('closeSearchBtn');

function toggleSearch(){
    const isHidden = searchBar.classList.contains('hidden');
    if (isHidden){
        searchBar.classList.remove('hidden');
        searchInput.focus();
    }else{
        searchBar.classList.add('hidden');
    }
}

searchToggleBtn.addEventListener('click', (e)=>{
    e.stopPropagation();
    toggleSearch();
});
closeSearchBtn.addEventListener('click', ()=>{
    searchBar.classList.add('hidden');
});

document.addEventListener('keydown', (e)=>{
    if (e.key === 'Escape' && !searchBar.classList.contains('hidden')){
        searchBar.classList.add('hidden');
    }
});

document.addEventListener('click', (e)=>{
    if (!searchBar.contains(e.target) && !searchToggleBtn.contains(e.target)){
        searchBar.classList.add('hidden');
    }
});