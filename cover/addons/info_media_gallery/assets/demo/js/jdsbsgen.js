window.addEventListener("load", function() {
    
    const cards = document.querySelectorAll('.image-card');
    
    cards.forEach(card => {
        
        const description = document.createElement('div');
        description.classList.add('image-description');
        description.innerHTML = card.dataset.desc;
        description.style.transform = "translateX(100%)";
        card.appendChild(description);

        card.addEventListener('mouseenter', function() {
           // console.log("Before mouseenter: ", description.style.transform);
            const rect = card.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            if (rect.right > viewportWidth / 2) {
                description.style.left = "auto";
                description.style.right = "100%";
                description.style.transform = "translateX(-100%)";
            } else {
                description.style.left = "auto";
                description.style.right = "100%";
                description.style.transform = "translateX(-100%)"; // buvo klaida
            }
          //  console.log("After mouseenter: ", description.style.transform);
        });
        
        card.addEventListener('mouseleave', function() {
            console.log("Before mouseenter: ", description.style.transform);
            description.style.transform = "translateX(100%)";
           // console.log("After mouseenter: ", description.style.transform);
        });
        
    });

});

