const searchItems = document.querySelector('#searchitems');
if (searchItems) {
  searchItems.addEventListener('input', async function() {
    const response = await fetch('../api/api_items.php?search=' + this.value);
    const items = await response.json();

    const section = document.querySelector('#items');
    section.innerHTML = '';

    for (const item of items) {
      const article = document.createElement('article');
      const img = document.createElement('img');
      img.src = 'https://picsum.photos/200?' + item.ItemId; 
      const link = document.createElement('a');
      link.href = '../pages/item.php?id=' + item.ItemId; 
      link.textContent = item.Title; 
      article.appendChild(img);
      article.appendChild(link);
      section.appendChild(article);
    }
  });
}
