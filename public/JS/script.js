// Fonction pour ajouter un produit au panier via une requête AJAX
function addToCart(productId, url) {
    // Création d'un objet JSON avec l'ID du produit
    const data = { productId: productId }; // Ajout de la clé productId dans l'objet JSON
    
    // Envoi de la requête AJAX au serveur pour ajouter le produit au panier
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
      // Affichage du message de confirmation
      console.log(data.status);
    })
    .catch(error => {
      console.error('Une erreur s\'est produite lors de l\'ajout du produit au panier:', error);
    });
  }
  
  // Écouteur d'événement pour le clic sur le bouton "Add to cart"
  document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.id;
        const url = this.getAttribute('data-url');
        addToCart(productId, "/product/addToCart");
      });
    });
  });