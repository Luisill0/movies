const previewImg = () => {
    const imageInput = document.getElementById('add-image');
    const imageInputLabel = document.getElementById('add-img-label');

    const file = imageInput.files[0];
    if(!file) return;
    
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
        const pfp = document.getElementById('pfp');
        if(pfp){
            pfp.src=reader.result;
        }else {
            const imageElement = document.createElement('img');
            imageElement.draggable = false;
            imageElement.src = reader.result;
            imageInputLabel.innerText = '';
            imageInputLabel.appendChild(imageElement);
        }
        const hiddenInput = document.getElementById('image-url');
        hiddenInput.value = reader.result;
    }
}