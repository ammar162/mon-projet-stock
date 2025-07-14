document.addEventListener("DOMContentLoaded", function() {
    // Si un élément avec la classe .success existe, afficher une alerte
    let successMsg = document.querySelector('.success');
    if (successMsg) {
        alert(successMsg.textContent);
    }

    // Confirmation avant suppression
    let deleteLinks = document.querySelectorAll('a[href*="delete_product.php"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
                e.preventDefault();
            }
        });
    });
});
