const container = document.querySelector('.container')

caches.open("V0").then(cache => {
    cache.match('/api/data').then(cachedLastPostResponse => {
        if (cachedLastPostResponse) {
            // Si la réponse est trouvée dans le cache, extraire les données
            cachedLastPostResponse.json().then(data => {
                // Utiliser les données pour afficher quelque chose sur la page
                // Utiliser les données pour afficher quelque chose sur la page
                const article = document.createElement('div')
                article.classList.add('article')
                container.appendChild(article)

                const title = document.createElement('h1')
                title.classList.add('article__title')

                title.innerHTML = data.title
                article.appendChild(title)

                const content = document.createElement('div');
                content.classList.add('content')
                content.innerHTML = data.content
                article.appendChild(content)
            })
        }
    })
})
