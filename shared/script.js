const navigate = (location) => {
    window.location.href = location;
}

const search = () => {
    const input = document.getElementById('search-bar-input');
    const query = input.value;
    if(query.length === 0){return;}
    location.href = `/movies/search.php?title=${query}`;
}